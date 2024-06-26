<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Service\XenditService;
use App\Models\Transaction;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentEmail;
use App\Mail\SuccessEmail;
use App\Mail\AccessGranted;
use App\Services\NotificationService;


class VirtualAccountPaymentController extends Controller
{
    public function vaTransaction(Request $request, XenditService $xenditService)
    {
        $headers = [
            'Authorization' => 'Basic ' . base64_encode(env('SECRET_KEY_XENDIT') . ':'),
        ];

        $validator = Validator::make($request->all(), [
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'payment_method' => 'required|in:VA,EWALLET',
            'bank_code' => 'required_if:payment_method,VA',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        // Retrieve all transactions with the given IDs
        $transactions = Transaction::whereIn('id', $request->transaction_ids)->get();
        $totalAmount = $transactions->sum('total_amount');

        // Generate external_id
        $externalId = 'fixed-va-' . now()->timestamp;
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $vaPayloads = [
                "external_id" => $externalId,
                "bank_code" => $request->bank_code,
                "name" => "GASCPNS " . Auth::user()->name,
                "is_closed" => true,
                "expected_amount" => $totalAmount,
                "expiration_date" => (new Carbon())->addHours(1)->toIso8601String(),
            ];

            $xenditResponse = $xenditService->createVa($vaPayloads);

            if (!$xenditResponse || !isset($xenditResponse['status'])) {
                throw new \Exception('Invalid Xendit response');
            }

            $bank = DB::table('banks')->where('code', $request->bank_code)->first();

            foreach ($transactions as $trx) {
                $trx->payment_method = $request->bank_code;
                $trx->payment_response = json_encode($xenditResponse);
                $trx->payment_token = $xenditResponse['external_id'];
                $trx->payment_expired = $xenditResponse['expiration_date'];
                $trx->payment_timer = 3600; // 1 jam
                $trx->payment_id = $xenditResponse['id'];
                $trx->payment_number = $xenditResponse['account_number'];
                $trx->payment_channel = "Virtual Account";
                $trx->payment_image = $bank->logo;
                $trx->payment_status = 'UNPAID';
                $trx->save();
            }

            Mail::to($user->email)->send(new PaymentEmail($user, $transactions->first()));

            NotificationService::sendNotification($user->id, 'Menunggu Pembayaran', 'Pembelian paket menunggu pembayaran. Silakan lakukan pembayaran sebelum ' . now()->parse($transactions->first()->payment_expired)->setTimezone('Asia/Jakarta')->format('d F Y H:i:s'), 'https://staging.gascpns.com/member/riwayat-transaksi');

            $responseData = [
                'transaction_ids' => $request->transaction_ids,
                'payment_response' => $xenditResponse,
            ];

            DB::commit();
            return ResponseFormatter::success($responseData, 'Anda memilih metode pembayaran Virtual Account, silakan selesaikan pembayaran Anda');

        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Failed to select payment method');
        }
    }



    /**
     * Callback for Xendit VA payment.
     */
    public function vaCallback(Request $request): void
    {
        if ($request->header("x-callback-token") != env("CALLBACK_XENDIT_TOKEN")) {
            abort(403);
        }

        // Log::info('Xendit VA Callback: ' . json_encode($data));

        $transactions = Transaction::where('payment_token', $request->callback_virtual_account_id)->get();

        if ($transactions->isEmpty()) {
            // Log error jika transaksi tidak ditemukan
            abort(404, 'Transaction not found');
        }

        // Dapatkan pengguna dan paket yang terkait dengan transaksi pertama
        $firstTransaction = $transactions->first();
        $user = $firstTransaction->studentTransaction;
        $package = $firstTransaction->package;

        foreach ($transactions as $trx) {
            $trx->payment_status = "PAID";
            $trx->payment_response = json_encode($request->all());
            $trx->payment_date = now();
            $trx->save();

            // Dapatkan student yang terkait dengan transaksi
            $student = $trx->student;

            // Beri user akses
            $student->packages()->attach($package->id, ['created_by' => '1 ']);

            // Mail to student
            Mail::to($student->email)->send(new AccessGranted($student, $trx));

            // Send notification to student
            NotificationService::sendNotification($student->id, 'Akses Paket', 'Anda telah mendapatkan akses ke paket ' . $package->name . '.', 'https://staging.gascpns.com/member/my-tryout');
        }

        // Mail to user
        Mail::to($user->email)->send(new SuccessEmail($user, $firstTransaction));
        // Send notification to user
        NotificationService::sendNotification($user->id, 'Pembayaran Berhasil', 'Pembelian paket ' . $package->name . ' telah berhasil.', 'https://staging.gascpns.com/member/riwayat-transaksi');

    }

}
