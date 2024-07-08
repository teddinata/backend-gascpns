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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentEmail;
use App\Mail\SuccessEmail;
use App\Services\NotificationService;


class EWalletPaymentController extends Controller
{
    /**
     * Create E-Wallet payment method.
     */
    public function ewalletTransaction(Request $request, XenditService $xenditService)
    {
        $validator = Validator::make($request->all(), [
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'payment_method' => 'required|in:EWALLET',
            'ewallet_type' => 'required|in:DANA,OVO,LINKAJA,SHOPEEPAY,ASTRAPAY',
            'success_redirect_url' => 'required_if:ewallet_type,DANA,LINKAJA,SHOPEEPAY,ASTRAPAY|url',
            'mobile_number' => 'required_if:ewallet_type,OVO'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $transactions = Transaction::whereIn('id', $request->transaction_ids)->get();
        $totalAmount = $transactions->sum('total_amount');
        $user = Auth::user();

        $ewalletType = $request->input('ewallet_type');
        $channelCode = '';
        $channelProperties = [];

        try {
            DB::beginTransaction();

            if ($ewalletType === 'DANA') {
                $channelCode = 'ID_DANA';
                $channelProperties['success_redirect_url'] = $request->input('success_redirect_url');
                $channelProperties['success_redirect_url'] = parse_url($channelProperties['success_redirect_url'], PHP_URL_SCHEME) . '://' . parse_url($channelProperties['success_redirect_url'], PHP_URL_HOST) . parse_url($channelProperties['success_redirect_url'], PHP_URL_PATH);

            } elseif ($ewalletType === 'OVO') {
                $channelCode = 'ID_OVO';
                $mobileNumber = $request->input('mobile_number');
                $mobileNumber = preg_replace('/[^0-9]/', '', $mobileNumber);

                if (Str::startsWith($mobileNumber, '+62')) {
                    $mobileNumber = '62' . substr($mobileNumber, 1);
                } elseif (Str::startsWith($mobileNumber, '62')) {
                    $mobileNumber = '62' . substr($mobileNumber, 2);
                } elseif (Str::startsWith($mobileNumber, '0')) {
                    $mobileNumber = '62' . ltrim($mobileNumber, '0');
                } else {
                    $mobileNumber = '62' . $mobileNumber;
                }
                $channelProperties['mobile_number'] = $mobileNumber;
            } elseif ($ewalletType === 'LINKAJA') {
                $channelCode = 'ID_LINKAJA';
                $channelProperties['success_redirect_url'] = $request->input('success_redirect_url');
            } elseif ($ewalletType === 'SHOPEEPAY') {
                $channelCode = 'ID_SHOPEEPAY';
                $channelProperties['success_redirect_url'] = $request->input('success_redirect_url');
            } elseif ($ewalletType === 'ASTRAPAY') {
                $channelCode = 'ID_ASTRAPAY';
                $channelProperties['success_redirect_url'] = $request->input('success_redirect_url');
                $channelProperties['failure_redirect_url'] = $request->input('failure_redirect_url');
            } else {
                $errors = [
                    'message' => 'Invalid E-wallet type.'
                ];
                return response()->json($errors, 422);
            }

            $ewalletPayloads = [
                'reference_id' => "reference-id-" . now()->timestamp,
                'currency' => 'IDR',
                'amount' => (int) $totalAmount,
                'checkout_method' => 'ONE_TIME_PAYMENT',
                'channel_code' => $channelCode,
                'channel_properties' => $channelProperties,
                'metadata' => [
                    'branch_code' => 'tree_branch'
                ]
            ];

            $xenditResponse = $xenditService->createEWallet($ewalletPayloads);

            if (!$xenditResponse || !isset($xenditResponse['status'])) {
                throw new \Exception('Invalid Xendit response');
            }

            $ewallet = DB::table('ewallets')->where('code', $ewalletType)->first();

            foreach ($transactions as $trx) {
                $trx->payment_method = $request->payment_method . '-' . $ewalletType;
                $trx->payment_response = json_encode($xenditResponse);
                $trx->payment_token = $xenditResponse['reference_id'];
                $trx->payment_expired = now()->addMinutes(15)->format('Y-m-d H:i:s');
                $trx->payment_timer = 3600; // 1 jam
                $trx->payment_id = $xenditResponse['id'];
                $trx->payment_channel = $channelCode;

                if ($ewalletType === 'SHOPEEPAY') {
                    $trx->payment_number = $xenditResponse['actions']['qr_checkout_string'];
                } elseif ($ewalletType === 'DANA' || $ewalletType === 'LINKAJA' || $ewalletType === 'ASTRAPAY') {
                    $trx->payment_number = $xenditResponse['actions']['mobile_web_checkout_url'];
                    $trx->payment_url = $xenditResponse['actions']['desktop_web_checkout_url'];
                } else {
                    $trx->payment_number = $xenditResponse['actions']['mobile_web_checkout_url'];
                }

                $trx->payment_image = $ewallet->logo;
                $trx->payment_status = 'UNPAID';
                $trx->save();
            }

            Mail::to($user->email)->send(new PaymentEmail($user, $transactions->first()));

            NotificationService::sendNotification($user->id, 'Menunggu Pembayaran', 'Pembelian paket menunggu pembayaran. Silakan lakukan pembayaran sebelum ' . $transactions->first()->payment_expired, 'https://app.gascpns.com/member/riwayat-transaksi');

            $responseData = [
                'transaction_ids' => $request->transaction_ids,
                'payment_response' => $xenditResponse
            ];

            DB::commit();
            return ResponseFormatter::success($responseData, 'Anda memilih metode pembayaran E-Wallet, silakan selesaikan pembayaran Anda');

        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Failed to select payment method');
        }
    }



    /**
     * Callback for Xendit E-Wallet payment.
     */

    public function ewalletCallback(Request $request)
    {
        if ($request->header("x-callback-token") != env("CALLBACK_XENDIT_TOKEN")) {
            abort(403);
        }

        if ($request->event == "ewallet.capture") {
            $transactions = Transaction::where('payment_token', $request->data['id'])->get();

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
                NotificationService::sendNotification($student->id, 'Akses Paket', 'Anda telah mendapatkan akses ke paket ' . $package->name . '.', 'https://app.gascpns.com/member/my-tryout');
            }

            NotificationService::sendNotification($user->id, 'Pembayaran Berhasil', 'Pembelian paket ' . $package->name . ' telah berhasil.', 'https://app.gascpns.com/member/riwayat-transaksi');
            Mail::to($user->email)->send(new SuccessEmail($user, $firstTransaction));
        }
    }

}
