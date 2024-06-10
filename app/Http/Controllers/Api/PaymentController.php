<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentInstruction;

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
}
