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
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'payment_method' => 'required|in:QRIS',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $transactions = Transaction::whereIn('id', $request->transaction_ids)->get();
        $totalAmount = $transactions->sum('total_amount');
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $qrisPayloads = [
                'reference_id' => "order-id-" . now()->timestamp,
                'type' => 'DYNAMIC',
                'currency' => 'IDR',
                'amount' => (int) $totalAmount,
                'expires_at' => now()->addHours(1)->toIso8601String(),
                'api_version' => '2022-07-31',
            ];

            $xenditResponse = $xenditService->createQr($qrisPayloads);

            if (!$xenditResponse || !isset($xenditResponse['status'])) {
                throw new \Exception('Invalid Xendit response');
            }

            foreach ($transactions as $trx) {
                $trx->payment_method = $request->payment_method;
                $trx->payment_response = json_encode($xenditResponse);
                $trx->payment_token = $xenditResponse['reference_id'];
                $trx->payment_expired = $xenditResponse['expires_at'];
                $trx->payment_timer = 3600; // 1 jam
                $trx->payment_id = $xenditResponse['id'];
                $trx->payment_number = $xenditResponse['qr_string'];
                $trx->payment_status = 'UNPAID';
                $trx->payment_channel = 'QRIS';
                $trx->save();
            }

            Mail::to($user->email)->send(new PaymentEmail($user, $transactions->first()));

            NotificationService::sendNotification($user->id, 'Menunggu Pembayaran', 'Pembelian paket menunggu pembayaran. Silakan lakukan pembayaran sebelum ' . now()->parse($transactions->first()->payment_expired)->setTimezone('Asia/Jakarta')->format('d F Y H:i:s'), 'https://staging.gascpns.com/member/riwayat-transaksi');

            $responseData = [
                'transaction_ids' => $request->transaction_ids,
                'payment_response' => $xenditResponse,
            ];

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

        $transactions = Transaction::where('payment_token', $request->data['qr_id'])->get();

        if ($transactions->isEmpty()) {
            abort(404, 'Transaction not found');
        }

        $firstTransaction = $transactions->first();
        $user = $firstTransaction->studentTransaction;
        $package = $firstTransaction->package;

        foreach ($transactions as $trx) {
            $trx->payment_status = "PAID";
            $trx->payment_response = json_encode($request->all());
            $trx->payment_date = now();
            $trx->save();

            $student = $trx->student;

            $student->packages()->attach($package->id, ['created_by' => '1 ']);

            Mail::to($student->email)->send(new AccessGranted($student, $trx));
            NotificationService::sendNotification($student->id, 'Akses Paket', 'Anda telah mendapatkan akses ke paket ' . $package->name . '.', 'https://staging.gascpns.com/member/my-tryout');
        }

        NotificationService::sendNotification($user->id, 'Pembayaran Berhasil', 'Pembelian paket ' . $package->name . ' telah berhasil.', 'https://staging.gascpns.com/member/riwayat-transaksi');
        Mail::to($user->email)->send(new SuccessEmail($user, $firstTransaction));
    }

}
