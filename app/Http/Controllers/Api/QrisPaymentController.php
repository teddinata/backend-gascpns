<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Service\XenditService;
use App\Models\Transaction;
use App\Helpers\ResponseFormatter;
use App\Mail\AccessGranted;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentEmail;
use App\Mail\SuccessEmail;
use App\Services\NotificationService;


class QrisPaymentController extends Controller
{
    /**
     * Create Xendit QRIS
     */
    public function qrisTransaction(Request $request, XenditService $xenditService)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'required|in:QRIS',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $transaction = Transaction::findOrFail($request->transaction_id);
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $qrisPayloads = [
                'reference_id' => "order-id-" . now()->timestamp,
                'type' => 'DYNAMIC',
                'currency' => 'IDR',
                'amount' => (int) $transaction->total_amount,
                // 'callback_url' => "https://ambarrukmo.page.link/Bk1pMEZG5Fk9dncs5", // Ganti dengan URL redirect yang sesuai
                'expires_at' => now()->addHours(1)->toIso8601String(),
                'api_version' => '2022-07-31',
            ];

            $xenditResponse = $xenditService->createQr($qrisPayloads);
            // dd($xenditResponse);

            if (!$xenditResponse || !isset($xenditResponse['status'])) {
                throw new \Exception('Invalid Xendit response');
            }

            $transaction->payment_method = $request->payment_method;
            $transaction->payment_response = json_encode($xenditResponse);
            $transaction->payment_token = $xenditResponse['id'];
            $transaction->payment_expired = $xenditResponse['expires_at'];
            $transaction->payment_timer = 3600; // 1 jam
            $transaction->payment_id = $xenditResponse['reference_id'];
            $transaction->payment_number = $xenditResponse['qr_string'];
            $transaction->payment_status = 'UNPAID';
            $transaction->payment_channel = 'QRIS';
            $transaction->save();

            // Send email
            Mail::to($user->email)->send(new PaymentEmail($user, $transaction));

            NotificationService::sendNotification($user->id, 'Menunggu Pembayaran', 'Pembelian paket ' . $transaction->package->name . ' menggunakan ' . $request->payment_method . ' menunggu pembayaran. Silakan lakukan pembayaran sebelum ' . now()->parse($transaction->payment_expired)->setTimezone('Asia/Jakarta')->format('d F Y H:i:s'), 'https://staging.gascpns.com/member/riwayat-transaksi');

            $responseData = [
                'transaction_id' => $transaction->id,
            ];

            $responseData['payment_response'] = $xenditResponse;

            DB::commit();
            return ResponseFormatter::success($responseData, 'Anda memilih metode pembayaran QRIS, silakan selesaikan pembayaran Anda');

        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Failed to select payment method');
        }
    }

     /**
     * Callback for Xendit QRIS payment.
     */
    public function qrisCallback(Request $request): void
    {
        if ($request->header("x-callback-token") != env("CALLBACK_XENDIT_TOKEN")) {
            abort(403);
        }

        $transaction = Transaction::where('payment_token', $request->data['qr_id'])->get();

        foreach ($transaction as $trx) {
            $trx->payment_status = "PAID";
            $trx->payment_response = json_encode($request->all());
            $trx->payment_date = now();
            $trx->save();

            // Dapatkan pengguna dan paket yang terkait dengan transaksi
            $student = $trx->student;
            $user    = $trx->studentTransaction;
            $package = $trx->package;

            // Lakukan aksi sesuai kebutuhan Anda
            $student->packages()->attach($package->id, ['created_by' => '1']);

            // Send notification to user
            NotificationService::sendNotification($user->id, 'Pembayaran Berhasil', 'Pembelian paket ' . $package->name . ' telah berhasil.', 'https://staging.gascpns.com/member/riwayat-transaksi');

            // Send notification to student
            NotificationService::sendNotification($student->id, 'Akses Paket', 'Anda telah mendapatkan akses ke paket ' . $package->name . '.', 'https://staging.gascpns.com/member/my-tryout');

                // mail to user
            Mail::to($user->email)->send(new SuccessEmail($user, $trx));
            Mail::to($student->email)->send(new AccessGranted($student, $trx));

        }
        // $transaction->payment_status = "PAID";
        // $transaction->payment_response = json_encode($request->all());
        // $transaction->payment_date = now();
        // $transaction->save();

        // // Dapatkan pengguna dan paket yang terkait dengan transaksi
        // $student = $transaction->student;
        // $package = $transaction->package;

        // // Lakukan aksi sesuai kebutuhan Anda
        // $student->packages()->attach($package->id, ['created_by' => '1']);
    }
}
