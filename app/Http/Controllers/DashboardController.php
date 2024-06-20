<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\CourseQuestion;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // data package yang akan ditampilkan di dashboard
        $packages = Package::all();
        // hitung jumlah paket yang akan ditampilkan
        $packagesCount = $packages->count();

        // count transaction with status paid
        $transactionPaid = Transaction::where('payment_status', 'PAID')->count();
        $transactionUnpaidAndPending = Transaction::whereIn('payment_status', ['UNPAID', 'PENDING'])->count();

        $totalSoal = CourseQuestion::all()->count();

        $totalUserActive = User::where('status', 'active')->count();

        // user where status is active and verified at != null
        $totalUserVerified = User::where('status', 'active')->whereNotNull('email_verified_at')->count();

        // total revenue from transaction
        $totalRevenue = Transaction::where('payment_status', 'PAID')->sum('total_amount');

        return view('dashboard-new',
            compact('packages', 'packagesCount', 'transactionPaid', 'totalSoal', 'totalUserActive', 'totalUserVerified',
            'transactionUnpaidAndPending', 'totalRevenue'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
