<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\TopUpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\PaymentEmail;
use App\Http\Service\XenditService;
use App\Mail\TopUpEmail;
use App\Services\NotificationService;

class TopUpController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:20000',
        ]);

        $transaction = new TopUpTransaction();
        $transaction->user_id = Auth::id();
        $transaction->amount = $request->amount;
        $transaction->save();

        return ResponseFormatter::success($transaction, 'Transaction created successfully');
    }

    public function handlePayment(Request $request, XenditService $xenditService)
    {
        $request->validate([
            'transaction_id' => 'required|exists:top_up_transactions,id',
            'payment_method' => 'required|in:VA,EWALLET,QRIS',
            'ewallet_type' => 'required_if:payment_method,EWALLET|in:DANA,OVO,LINKAJA,SHOPEEPAY,ASTRAPAY',
            'success_redirect_url' => 'required_if:ewallet_type,DANA,LINKAJA,SHOPEEPAY,ASTRAPAY|url',
            'mobile_number' => 'required_if:ewallet_type,OVO'
        ]);

        $transaction = TopUpTransaction::findOrFail($request->transaction_id);
        $user = Auth::user();
        $paymentMethod = $request->input('payment_method');
        $amount = $transaction->amount;

        try {
            DB::beginTransaction();

            if ($paymentMethod === 'VA') {
                // Handle VA payment
                $channelCode = $request->input('bank_code');
                $vaPayloads = [
                    'external_id' => "va-topup-" . now()->timestamp,
                    'bank_code' => $channelCode,
                    'name' => "TOPUP GASCPNS " . $user->name,
                    'expected_amount' => $amount,
                    "is_closed" => true,
                    "expiration_date" => (new Carbon())->addHours(1)->toIso8601String(),
                ];
                $xenditResponse = $xenditService->createVa($vaPayloads);
                $transaction->payment_number = $xenditResponse['account_number'];
            } elseif ($paymentMethod === 'EWALLET') {
                // Handle Ewallet payment
                $ewalletType = $request->input('ewallet_type');
                $channelCode = 'ID_' . strtoupper($ewalletType);
                $channelProperties = [
                    'success_redirect_url' => $request->input('success_redirect_url'),
                ];

                if ($ewalletType === 'OVO') {
                    $mobileNumber = preg_replace('/[^0-9]/', '', $request->input('mobile_number'));
                    $channelProperties['mobile_number'] = $mobileNumber;
                }

                $ewalletPayloads = [
                    'reference_id' => "ewallet-topup-" . now()->timestamp,
                    'currency' => 'IDR',
                    'amount' => (int) $amount,
                    'checkout_method' => 'ONE_TIME_PAYMENT',
                    'channel_code' => $channelCode,
                    'channel_properties' => $channelProperties,
                ];
                $xenditResponse = $xenditService->createEWallet($ewalletPayloads);
                $transaction->payment_number = $ewalletType === 'SHOPEEPAY' ? $xenditResponse['actions']['qr_checkout_string'] : $xenditResponse['actions']['mobile_web_checkout_url'];
                $transaction->payment_url = $ewalletType === 'DANA' ? $xenditResponse['actions']['desktop_web_checkout_url'] : null;
            } elseif ($paymentMethod === 'QRIS') {
                // Handle QRIS payment
                $qrisPayloads = [
                    // 'external_id' => "qris-topup-" . now()->timestamp,
                    'amount' => (int) $amount,
                    'type' => 'DYNAMIC',
                    'reference_id' => "qris-topup-" . now()->timestamp,
                    'currency' => 'IDR',
                    'expires_at' => now()->addHours(1)->toIso8601String(),
                    'api_version' => '2022-07-31',
                ];
                $xenditResponse = $xenditService->createQr($qrisPayloads);
                $transaction->payment_number = $xenditResponse['qr_string'];
            }

            $transaction->payment_method = $paymentMethod . ($paymentMethod === 'EWALLET' ? '-' . $request->input('ewallet_type') : '') . ($paymentMethod === 'VA' ? '-' . $request->input('bank_code') : '');
            $transaction->payment_channel = $paymentMethod;
            $transaction->payment_response = json_encode($xenditResponse);
            $transaction->payment_status = 'UNPAID';
            $transaction->payment_expired = now()->addMinutes(60)->format('Y-m-d H:i:s');
            // $transaction->payment_expired = (new Carbon())->addHours(1)->toIso8601String();
            $transaction->save();

            Mail::to($user->email)->send(new TopUpEmail($user, $transaction));

            // notification
            NotificationService::sendNotification($user->id, 'Permintaan Top-Up', 'Permintaan top-up sebesar Rp' . number_format($amount, 0, ',', '.') . ' telah kami terima. Silahkan selesaikan pembayaran sebelum batas waktu yang ditentukan.', 'https://app.gascpns.com/member/riwayat-transaksi');

            DB::commit();
            return response()->json(['transaction' => $transaction, 'payment_response' => $xenditResponse], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // Endpoint untuk cek status pembayaran dari transaction.id
    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:top_up_transactions,id',
        ]);

        $transaction = TopUpTransaction::findOrFail($request->transaction_id);
        $paymentStatus = $transaction->payment_status;

        if ($paymentStatus === 'PAID') {
            return response()->json(['message' => 'Pembayaran sudah diterima', 'status' => 'PAID'], 200);
        }

        if ($paymentStatus === 'EXPIRED') {
            return response()->json(['message' => 'Transaksi sudah kadaluarsa', 'status' => 'EXPIRED'], 200);
        }

        if ($paymentStatus === 'PENDING') {
            return response()->json(['message' => 'Transaksi masih tertunda belum terbayar', 'status' => 'PENDING'], 200);
        }

        if ($paymentStatus === 'UNPAID') {
            return response()->json(['message' => 'Transaksi belum terbayar, silahkan coba lagi nanti atau hubungi CS.', 'status' => 'PENDING'], 200);
        }

        return response()->json(['message' => 'Transaksi tidak ditemukan', 'status' => 'NOT_FOUND'], 404);
    }

    // Callback untuk menerima notifikasi dari Xendit
    public function paymentCallback(Request $request): void
    {
        if ($request->header("x-callback-token") != env("CALLBACK_XENDIT_TOKEN")) {
            abort(403);
        }

        // Log payload dari Xendit
        // Log::info('Xendit Callback: ' . json_encode($request->all()));

        $transaction = TopUpTransaction::where('payment_id', $request->external_id)->first();

        if (!$transaction) {
            // Log error jika transaksi tidak ditemukan
            abort(404, 'Transaction not found');
        }

        $transaction->payment_status = "PAID";
        $transaction->payment_response = json_encode($request->all());
        $transaction->payment_date = now();
        $transaction->save();

        // Tambahkan saldo ke user
        $user = $transaction->user;
        $user->wallet_balance += $transaction->amount;
        $user->save();

        // Mail to user
        // Mail::to($user->email)->send(new TopUpEmail($user, $transaction));

        // Send notification to user
        NotificationService::sendNotification($user->id, 'Top-Up Berhasil', 'Saldo sebesar Rp' . number_format($transaction->amount, 0, ',', '.') . ' telah ditambahkan ke akun Anda.', 'https://app.gascpns.com/member/riwayat-transaksi');
    }

    /**
     * Get top-up history for the authenticated user.
     */
    public function topupHistory(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return ResponseFormatter::error(null, 'User not authenticated', 401);
        }

        $topupHistory = TopUpTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // if now greater than payment_expired, set payment_status to EXPIRED
        $topupHistory->each(function ($transaction) {
            if (now() > $transaction->payment_expired && $transaction->payment_status === 'UNPAID') {
                $transaction->payment_status = 'EXPIRED';
                $transaction->save();
            }
        });

        return ResponseFormatter::success($topupHistory, 'Top-up history retrieved successfully');
    }
    public function show($id)
    {
        $transaction = TopUpTransaction::findOrFail($id);

        return ResponseFormatter::success($transaction, 'Transaction details retrieved successfully');
    }

}
