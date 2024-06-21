<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\ResponseFormatter;
use App\Models\TryOut;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $user = Auth::user();

        $totalTransaction = $user->transactions()->sum('total_amount');
        $totalPurchasedPackage = $user->enrolledPackageTryouts()->count();
        $totalTopUp = $user->topUpTransactions()->sum('amount');
        $totalTryoutWorked = TryOut::where('user_id', $user->id)->whereNotNull('finished_at')->count() ?? 0;
        $totalUser = User::count();

        return ResponseFormatter::success([
            'total_transaction' => $totalTransaction,
            'total_purchased_package' => $totalPurchasedPackage,
            'total_top_up' => $totalTopUp,
            'total_tryout_worked' => $totalTryoutWorked,
            'total_user' => $totalUser,
        ], 'Data dashboard berhasil diambil');
    }
}
