<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    // Menampilkan daftar voucher
    public function index()
    {
        $vouchers = Voucher::all();
        return view('admin.vouchers.index', compact('vouchers'));
    }

    // Menampilkan halaman form untuk membuat voucher baru
    public function create()
    {
        return view('admin.vouchers.create');
    }

    // Menyimpan voucher baru ke database
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:vouchers,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'required|boolean',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_user' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Voucher::create($request->all());

        return redirect()->route('dashboard.vouchers.index')->with('success', 'Voucher created successfully');
    }

    // Menampilkan halaman form untuk mengedit voucher
    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit', compact('voucher'));
    }

    // Memperbarui voucher yang ada di database
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'required|boolean',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_per_user' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $voucher->update($request->all());

        return redirect()->route('dashboard.vouchers.index')->with('success', 'Voucher updated successfully');
    }

    // Menghapus voucher dari database
    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('dashboard.vouchers.index')->with('success', 'Voucher deleted successfully');
    }
}
