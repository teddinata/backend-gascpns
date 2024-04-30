<?php

namespace App\Http\Controllers;

use App\Models\PackageTryOut;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Course;


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
    public function create(Package $package)
    {
        // tampilkan courses yang belum ada di package
        $courses = Course::whereNotIn('id', $package->packageTryOuts->pluck('course_id'))->get();

        $package_tryout = PackageTryOut::where('package_id', $package->id)->get();

        // hitung total tryout courses dari relasi package tryout dengan courses -> question
        $total_tryout_courses = 0;
        foreach ($package_tryout as $tryout) {
            $total_tryout_courses += $tryout->course->questions->count();
        }

        return view('admin.tryout-packages.create', compact('package', 'courses', 'total_tryout_courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Package $package)
    {
        // Validate the request...
        $validatedData = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        // Store the data...
        DB::beginTransaction();
        try {
            $package->packageTryOuts()->create($validatedData);
            DB::commit();

            return redirect()->route('dashboard.packages.show', $package->id)->with('success', 'Package try out created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Package try out failed to create '. $e->getMessage()
                ]
            ]);
            throw $error;
        }
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
    public function edit(PackageTryOut $package_tryout)
    {
        // dd($package_tryout);
        $package = Package::findOrFail($package_tryout->package_id);
        // Tampilkan courses yang belum ada di package
        $courses = Course::whereNotIn('id', $package->packageTryOuts->pluck('course_id'))->get();

        // Tampilkan package try out
        // $package = PackageTryOut::with('package')->findOrFail($package_tryout->id);



        return view('admin.tryout-packages.edit', compact('package_tryout', 'package', 'courses'));
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
    public function destroy(PackageTryOut $package_tryout)
    {
        // delete the data...
        DB::beginTransaction();
        try {
            $package_tryout->delete();
            DB::commit();

            return redirect()->route('dashboard.packages.show', $package_tryout->package_id)->with('success', 'Package try out deleted successfully');
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
