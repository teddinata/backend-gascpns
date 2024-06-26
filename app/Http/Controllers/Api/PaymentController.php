<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentInstruction;
use App\Models\TopUpTransaction;
use App\Models\Transaction;
use App\Mail\TopUpEmail;
use App\Mail\AccessGranted;
use App\Mail\SuccessEmail;
use Illuminate\Support\Facades\Mail;
use App\Services\NotificationService;

class PaymentController extends Controller
{
    // show list available bank
    public function getAvailableBanks()
    {
        // get all data from list_available_banks table without created at and updated at column
        $list_available_banks = \DB::table('banks')->select('id', 'name', 'code', 'country', 'currency', 'is_activated', 'logo')
            ->where('is_activated', 1)
            ->get();

        // return response in json format
        return response()->json([
            'success' => true,
            'message' => 'List of available banks',
            'data' => $list_available_banks
        ], 200);
    }

    // function to get list of available ewallets
    public function getAvailableEWallets()
    {
        // get all data from list_ewallets table without created at and updated at column and show is actiavted 1 will be true response and 0 will be false response not show in response data as 1 or 0
        $list_ewallets = \DB::table('ewallets')->select('id', 'name', 'code', 'ewallet_type', 'country', 'currency', 'is_activated', 'logo')
            ->where('is_activated', 1)
            ->get();

        // return response in json format
        return response()->json([
            'success' => true,
            'message' => 'List of available ewallets',
            'data' => $list_ewallets
        ], 200);
    }

    public function getPaymentInstructions($bank)
    {
        $instructions = PaymentInstruction::where('bank_code', $bank)->get();

        $groupedInstructions = $instructions->groupBy('method')->map(function ($groupedInstructions) {
            return $groupedInstructions->map(function ($instruction) {
                return [
                    'id' => $instruction->id,
                    'bank_code' => $instruction->bank_code,
                    'method' => $instruction->method,
                    'title' => $instruction->title,
                    'instructions' => $instruction->instructions,
                    'created_at' => $instruction->created_at,
                    'updated_at' => $instruction->updated_at,
                ];
            });
        });

        return response()->json(['data' => $groupedInstructions]);
    }

    public function unifiedCallback(Request $request): void
    {
        if ($request->header("x-callback-token") != env("CALLBACK_XENDIT_TOKEN")) {
            abort(403);
        }

        // Log payload dari Xendit untuk debugging
        // Log::info('Xendit Callback: ' . json_encode($request->all()));

        if (isset($request->callback_virtual_account_id)) {
            $this->handleVACallback($request);
        } elseif (isset($request->data['qr_id'])) {
            $this->handleQrisCallback($request);
        } elseif ($request->event == "ewallet.capture") {
            $this->handleEwalletCallback($request);
        } else {
            // Log error jika jenis transaksi tidak dikenali
            abort(400, 'Invalid callback data');
        }
    }

    private function handleVACallback(Request $request)
    {
        $this->handleCallback($request, 'callback_virtual_account_id', TopUpTransaction::class);
        $this->handleCallback($request, 'callback_virtual_account_id', Transaction::class);
    }

    private function handleQrisCallback(Request $request)
    {
        $this->handleCallback($request, 'data.qr_id', TopUpTransaction::class);
        $this->handleCallback($request, 'data.qr_id', Transaction::class);
    }

    private function handleEwalletCallback(Request $request)
    {
        $this->handleCallback($request, 'data.id', TopUpTransaction::class);
        $this->handleCallback($request, 'data.id', Transaction::class);
    }

    private function handleCallback(Request $request, $identifier, $modelClass)
    {
        $identifierValue = $this->getIdentifierValue($request, $identifier);

        $transaction = $modelClass::where('payment_id', $identifierValue)->first();

        if (!$transaction) {
            return; // Skip if transaction is not found for this model
        }

        $transaction->payment_status = "PAID";
        $transaction->payment_response = json_encode($request->all());
        $transaction->payment_date = now();
        $transaction->save();

        if ($modelClass === TopUpTransaction::class) {
            $user = $transaction->user;
            $user->wallet_balance += $transaction->amount;
            $user->save();

            NotificationService::sendNotification($user->id, 'Top-Up Berhasil', 'Saldo sebesar Rp' . number_format($transaction->amount, 0, ',', '.') . ' telah ditambahkan ke akun Anda.', 'https://app.gascpns.com/member/riwayat-transaksi');
        } else {
            $this->handleTransactionCallback($transaction, $request);
        }
    }

    private function getIdentifierValue(Request $request, $identifier)
    {
        $parts = explode('.', $identifier);
        $value = $request;
        foreach ($parts as $part) {
            $value = $value[$part];
        }
        return $value;
    }

    private function handleTransactionCallback($transaction, Request $request)
    {
        $user = $transaction->studentTransaction;
        $package = $transaction->package;

        $student = $transaction->student;
        $student->packages()->attach($package->id, ['created_by' => '1 ']);

        Mail::to($student->email)->send(new AccessGranted($student, $transaction));
        NotificationService::sendNotification($student->id, 'Akses Paket', 'Anda telah mendapatkan akses ke paket ' . $package->name . '.', 'https://staging.gascpns.com/member/my-tryout');

        NotificationService::sendNotification($user->id, 'Pembayaran Berhasil', 'Pembelian paket ' . $package->name . ' telah berhasil.', 'https://staging.gascpns.com/member/riwayat-transaksi');
        Mail::to($user->email)->send(new SuccessEmail($user, $transaction));
    }
}
