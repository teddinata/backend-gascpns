<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Transaction::query();

        // Filter berdasarkan invoice code jika ada
        if ($request->has('invoice_code')) {
            $query->where('invoice_code', $request->invoice_code);
        }

        // Filter berdasarkan status pembayaran jika ada
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Anda dapat menambahkan filter tambahan sesuai kebutuhan, seperti nama paket, tanggal pembayaran, dll.

        $transactions = $query->with('details')->orderBy('created_at', 'DESC')->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
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
        // Mengambil transaksi beserta detailnya
        $transaction = Transaction::with('details')->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
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
