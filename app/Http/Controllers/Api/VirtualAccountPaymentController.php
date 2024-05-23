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



class VirtualAccountPaymentController extends Controller
{
    public function vaTransaction(Request $request, XenditService $xenditService)
    {
        // Authourization Basic xnd_development_SoKkZv7IZIJqdQFXvSS6SyDGuQpPXUq2pXWvT3QxsDePbNwsv4gKv05A1qcKO0
        $headers = [
            'Authorization' => 'Basic ' . base64_encode(env('SECRET_KEY_XENDIT') . ':'),
        ];


        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'required|in:VA,EWALLET',
            'bank_code' => 'required_if:payment_method,VA',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $transaction = Transaction::findOrFail($request->transaction_id);
        // $now = new DateTime();

        try {

            DB::beginTransaction();

            $vaPayloads = [
                "external_id" => "fixed-va-" . now()->timestamp,
                "bank_code" => $request->bank_code,
                "name" => "Ambarrukmo " . Auth::user()->name,
                "is_closed" => true,
                "expected_amount" => $transaction->total_amount,
                "expiration_date" => (new Carbon())->addHours(1)->toIso8601String(),
            ];

                $xenditResponse = $xenditService->createVa($vaPayloads);
                // dd($xenditResponse);


            if (!$xenditResponse || !isset($xenditResponse['status'])) {
                throw new \Exception('Invalid Xendit response');
            }

            $transaction->payment_method = "Virtual Account" . " - " . $request->bank_code;
            $transaction->payment_response = json_encode($xenditResponse);
            $transaction->payment_token = $xenditResponse['id'];
            $transaction->payment_expired = $xenditResponse['expiration_date'];
            $transaction->payment_timer = 3600; // 1 jam
            $transaction->payment_id = $xenditResponse['external_id'];
            $transaction->payment_number = $xenditResponse['account_number'];
            $transaction->save();

            $responseData = [
                'transaction_id' => $transaction->id,
            ];

            $responseData['payment_response'] = $xenditResponse;

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

        $transaction = Transaction::where('payment_token', $request->callback_virtual_account_id)->first();

        if (!$transaction) {
            // Log error jika transaksi tidak ditemukan
            abort(404, 'Transaction not found');
        }

        foreach ($transaction as $trx) {
            $trx->payment_status = "PAID";
            $trx->payment_response = json_encode($request->all());
            $trx->payment_date = now();
            $trx->save();

            // Dapatkan pengguna dan paket yang terkait dengan transaksi
            $student = $trx->student;
            $package = $trx->package;

            // beri user akses]
            $student->packages()->attach($package->id, ['created_by' => '1 ']);
        }

        // $transaction->payment_status = "PAID";
        // $transaction->payment_response = json_encode($request->all());
        // $transaction->payment_date = now();
        // $transaction->save();

        // // Dapatkan pengguna dan paket yang terkait dengan transaksi
        // $student = $transaction->student;
        // $package = $transaction->package;

        // // beri user akses
        // $student->packages()->attach($package->id, ['created_by' => '1 ']);
    }
}