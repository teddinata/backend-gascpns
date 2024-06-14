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


class EWalletPaymentController extends Controller
{
    /**
     * Create E-Wallet payment method.
     */
    public function ewalletTransaction(Request $request, XenditService $xenditService)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'required|in:EWALLET',
            // 'ewallet' => 'required|in:OVO,DANA,LinkAja',
            // 'success_redirect_url' => 'required_if:ewallet_type,DANA,LINKAJA|url',
            'ewallet_type' => 'required|in:DANA,OVO,LINKAJA',
            'success_redirect_url' => 'required_if:ewallet_type,DANA,LINKAJA|url',
            'mobile_number' => 'required_if:ewallet_type,OVO'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $transaction = Transaction::findOrFail($request->transaction_id);
        $user = Auth::user();

        // Tentukan channel_code berdasarkan E-wallet yang dipilih oleh pengguna
        $ewalletType = $request->input('ewallet_type');
        $channelCode = '';
        $channelProperties = [];

        try {
            DB::beginTransaction();

            if ($ewalletType === 'DANA') {
                $channelCode = 'ID_DANA';

                // Dana menggunakan "success_redirect_url"
                // parsing halaman https://ambarrukmo.page.link/Bk1pMEZG5Fk9dncs5 agar tidak menjadi intent
                $channelProperties['success_redirect_url'] = $request->input('success_redirect_url'); // Ganti dengan URL redirect yang sesuai
                $channelProperties['success_redirect_url'] = parse_url($channelProperties['success_redirect_url'], PHP_URL_SCHEME) . '://' . parse_url($channelProperties['success_redirect_url'], PHP_URL_HOST) . parse_url($channelProperties['success_redirect_url'], PHP_URL_PATH);

                // Ambil nomor hp (mobile number) dari input
            } elseif ($ewalletType === 'OVO') {
                $channelCode = 'ID_OVO';

                // Ambil nomor hp (mobile number) dari input
                $mobileNumber = $request->input('mobile_number');
                // Hilangkan karakter selain angka dari nomor ponsel
                $mobileNumber = preg_replace('/[^0-9]/', '', $mobileNumber);
                // handle mobile number agar yang diinputkan 0, 62, atau +62 menjadi 62
                if (Str::startsWith($mobileNumber, '+62')) {
                    $mobileNumber = '62' . substr($mobileNumber, 1);
                } elseif (Str::startsWith($mobileNumber, '62')) {
                    $mobileNumber = '62' . substr($mobileNumber, 2);
                } elseif (Str::startsWith($mobileNumber, '0')) {
                    $mobileNumber = '62' . ltrim($mobileNumber, '0');
                } else {
                    $mobileNumber = '62' . $mobileNumber;
                }

                // Tambahkan nomor hp ke dalam channel_properties
                $channelProperties['mobile_number'] = $mobileNumber;
            } elseif ($ewalletType === 'LINKAJA') {
                $channelCode = 'ID_LINKAJA';

                // Tambahkan success_redirect_url ke dalam channel_properties untuk LINKAJA
                $channelProperties['success_redirect_url'] = 'https://ambarrukmo.page.link/Bk1pMEZG5Fk9dncs5'; // Ganti dengan URL redirect yang sesuai
            } else {
                // Jika E-wallet yang dipilih tidak valid, berikan respon error
                $errors = [
                    'message' => 'Invalid E-wallet type.'
                ];
                return response()->json($errors, 422);
            }



            $ewalletPayloads = [
                'reference_id' => "reference-id-" . now()->timestamp, // Gunakan ID event transaction sebagai reference_id
                'currency' => 'IDR',
                'amount' => (int) $transaction->total_amount,
                'checkout_method' => 'ONE_TIME_PAYMENT',
                'channel_code' => $channelCode, // Ganti dengan channel code yang sesuai dengan E-wallet yang ingin digunakan
                'channel_properties' => $channelProperties,
                'metadata' => [
                    'branch_code' => 'tree_branch'
                ]
            ];

            $xenditResponse = $xenditService->createEWallet($ewalletPayloads);
            // dd($xenditResponse);

            // get image from available_ewallets table
            $ewallet = DB::table('ewallets')->where('code', $ewalletType)->first();

            if (!$xenditResponse || !isset($xenditResponse['status'])) {
                throw new \Exception('Invalid Xendit response');
            }

            $transaction->payment_method = $request->payment_method . '-' . $ewalletType;
            $transaction->payment_response = json_encode($xenditResponse);
            $transaction->payment_token = $xenditResponse['id'];
            $transaction->payment_expired = now()->addMinutes(15)->format('Y-m-d H:i:s');
            $transaction->payment_timer = 3600; // 1 jam
            $transaction->payment_id = $xenditResponse['reference_id'];
            $transaction->payment_channel = $channelCode;
            $transaction->payment_number = $xenditResponse['actions']['mobile_web_checkout_url'];
            $transaction->payment_image = $ewallet->logo;
            $transaction->payment_status = 'UNPAID';
            $transaction->save();

            // Send email
            Mail::to($user->email)->send(new PaymentEmail($user, $transaction));

            $responseData = [
                'transaction_id' => $transaction->id,
                'payment_response' => $xenditResponse
            ];

            $responseData['payment_response'] = $xenditResponse;

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

         if($request->event == "ewallet.capture") {
             $transaction = Transaction::where('payment_token', $request->data['id'])->get();
            //  $transaction = Transaction::where('payment_id', $request->reference_id)->get();

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

                // mail to user
                Mail::to($user->email)->send(new SuccessEmail($user, $trx));
                Mail::to($student->email)->send(new AccessGranted($student, $trx));


             }
         }

     }
}
