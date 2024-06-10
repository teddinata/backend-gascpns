<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'per_page' => 'nullable',
            'keyword' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $filters['per_page'] = !empty($filters['per_page']) ? $filters['per_page'] : 15;

        $where[] = ['name', '!=', ''];

        if (!empty($filters['keyword'])) {
            $where[] = ['name', 'like', '%'.$filters['keyword'].'%'];
        }

        if (!empty($filters['is_activated'])) {
            $is_activated = ($filters['is_activated'] == '-1') ? 0 : 1;
            $where[] = ['is_activated', $is_activated];
        }

        // get list available bank and list ewallet by filter
        $list_available_banks = DB::table('banks')
            ->where($where)
            ->paginate($filters['per_page']);

        $list_ewallets = DB::table('ewallets')
            ->where($where)
            ->paginate($filters['per_page']);

        return view('admin.payment-methods.index', compact('list_available_banks', 'list_ewallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get data list available bank and list ewallet
        $list_available_banks = DB::table('banks')->get();

        $list_ewallets = DB::table('ewallets')->get();

        return view('admin.payment-methods.edit', compact('list_available_banks', 'list_ewallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // store data to database
         // update data
         $data = $request->validate([
            'list_available_banks' => 'nullable|array',
            'list_ewallets' => 'nullable|array',
        ]);
        // dd($data);

        // Update list available bank
        if (!empty($data['list_available_banks'])) {
            foreach ($data['list_available_banks'] as $bankId => $isActivated) {
                $isActivated = isset($isActivated) ? $isActivated : 0; // Set to 0 if not checked
                DB::table('banks')
                    ->where('id', $bankId)
                    ->update([
                        'is_activated' => $isActivated,
                    ]);
            }
        }

        // update list ewallet
        if (!empty($data['list_ewallets'])) {
            foreach ($data['list_ewallets'] as $ewalletId => $isActivated) {
                $isActivated = isset($isActivated) ? $isActivated : 0; // Set to 0 if not checked
                DB::table('ewallets')
                    ->where('id', $ewalletId)
                    ->update([
                        'is_activated' => $isActivated,
                    ]);
            }
        }

        return redirect()->route('dashboard.payment-methods.index')->with('success', 'Payment methods updated successfully');
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
