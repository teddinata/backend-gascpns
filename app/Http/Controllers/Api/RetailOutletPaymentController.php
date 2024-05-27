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

class RetailOutletPaymentController extends Controller
{
    // Create Retail Outlet payment method
    public function retailOutletTransaction(Request $request, XenditService $xenditService)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'required|in:RETAIL_OUTLET',
            'retail_outlet' => 'required|in:ALFAMART,INDOMARET',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error($validator->errors(), 'Validation Error', 422);
        }

        $transaction = Transaction::findOrFail($request->transaction_id);

        try {
            DB::beginTransaction();

            $retailOutletPayloads = [
                'external_id' => "order-id-" . now()->timestamp,
                'retail_outlet_name' => $request->retail_outlet,
                'name' => "GASCPNS " . Auth::user()->name,
                'expected_amount' => $transaction->total_amount + 5000, // Add 5000 for admin fee
                'expiration_date' => (new Carbon())->addHours(1)->toIso8601String(),
            ];

            // dd($retailOutletPayloads);
            $xenditResponse = $xenditService->createRetailOutlet($retailOutletPayloads);

            if (!$xenditResponse) {
                throw new \Exception('Invalid Xendit response');
            }
            // dd($xenditResponse);

            $transaction->payment_method = $request->payment_method . ' - ' . $request->retail_outlet;
            $transaction->payment_response = json_encode($xenditResponse);
            $transaction->payment_token = $xenditResponse['id'];
            $transaction->payment_expired = $xenditResponse['expiration_date'];
            $transaction->payment_timer = 3600; // 1 hour
            $transaction->payment_id = $xenditResponse['external_id'];
            $transaction->payment_number = $xenditResponse['payment_code'];
            $transaction->payment_status = 'UNPAID';
            $transaction->payment_channel = 'RETAIL_OUTLET';
            $transaction->save();
            // dd($transaction);
            DB::commit();

            return ResponseFormatter::success($xenditResponse, 'Retail Outlet payment created successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return ResponseFormatter::error($exception->getMessage(), 'Retail Outlet payment failed');
        }

    }

    /**
     * Callback for Xendit Retail Outlet payment.
     */
    public function retailOutletCallback(Request $request)
    {
        // implement callback logic here
        if ($request->header("x-callback-token") != env("CALLBACK_XENDIT_TOKEN")) {
            abort(403);
        }

        if ($request->event == "retail_outlet.capture") {
            $transaction = Transaction::where('payment_token', $request->data['id'])->get();

            foreach ($transaction as $trx) {
                $trx->payment_status = "PAID";
                $trx->payment_response = json_encode($request->all());
                $trx->payment_date = now();
                $trx->save();
            }
        }
    }
}
