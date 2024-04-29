<?php

namespace App\Http\Controllers;

use App\Models\PackageTryOut;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class PackageTryOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...

    }

    /**
     * Display the specified resource.
     */
    public function show(PackageTryOut $packageTryOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        // Validate the request...

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PackageTryOut $packageTryOut)
    {
        // delete the data...
        DB::beginTransaction();
        try {
            $packageTryOut->delete();
            DB::commit();

            return redirect()->route('dashboard.packages.index')->with('success', 'Package deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Package failed to delete '. $e->getMessage()
                ]
            ]);
            throw $error;
        }
    }
}
