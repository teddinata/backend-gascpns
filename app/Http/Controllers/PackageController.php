<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\PackageTryOut;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = Package::paginate(10);
        return view('admin.packages.index', compact('packages'));
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
        // dd($request['status']);
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'price'             => 'required|numeric|min:0',
            'total_question'    => 'required|integer|min:1',
            'total_duration'    => 'required|integer|min:1',
            'status'            => 'nullable|boolean',
            'sale_start_at'     => 'nullable|date_format:Y-m-d\TH:i',
            'sale_end_at'       => 'nullable|date_format:Y-m-d\TH:i',
            'discount'          => 'nullable|numeric|min:0|max:' . $request['price'],
            'voucher_code'      => 'nullable|string|max:255',
            'cover_path'        => 'nullable|image|max:2048',
            'thumbnail_path'    => 'nullable|image|max:2048',
        ]);

        // Store the data...
        DB::beginTransaction();
        try {
            // store the cover image
            if ($request->hasFile('cover_path')) {
                $coverPath = $request->file('cover_path')->store('package_cover', 'public');
                $validatedData['cover_path'] = $coverPath;
            }

            // store the thumbnail image
            if ($request->hasFile('thumbnail_path')) {
                $thumbnailPath = $request->file('thumbnail')->store('package_thumbnail', 'public');
                $validatedData['thumbnail_path'] = $thumbnailPath;
            }

            // Konversi format tanggal dan waktu
            if ($request->has('sale_start_at')) {
                $validatedData['sale_start_at'] = date('Y-m-d H:i:s', strtotime($validatedData['sale_start_at']));
            }

            if ($request->has('sale_end_at')) {
                $validatedData['sale_end_at'] = date('Y-m-d H:i:s', strtotime($validatedData['sale_end_at']));
            }

            $validatedData['slug'] = Str::slug($validatedData['name']);
            $validatedData['status'] = $request->has('status') ? 1 : 0;

            Package::create($validatedData);

            DB::commit();

            return redirect()->route('dashboard.packages.index')->with('success', 'Package created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Course failed to create '. $e->getMessage()
                ]
            ]);
            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        // package tryout
        $package_tryout = PackageTryOut::where('package_id', $package->id)->get();

        // hitung total tryout courses dari relasi package tryout dengan courses -> question
        $total_tryout_courses = 0;
        foreach ($package_tryout as $tryout) {
            $total_tryout_courses += $tryout->course->questions->count();
        }

        $title = 'Delete Tryout dari Paket?';
        $text = "Apakah anda yakin ingin menghapus tryout dari paket ini?";
        confirmDelete($title, $text);

        // dd($total_tryout_courses);
        return view('admin.packages.manage-tryout', compact('package', 'package_tryout', 'total_tryout_courses'));
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
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'total_question'    => 'required|integer|min:1',
            'total_duration'    => 'required|integer|min:1',
            'status'            => 'nullable|boolean',
            'sale_start_at'     => 'nullable|date_format:Y-m-d\TH:i',
            'sale_end_at'       => 'nullable|date_format:Y-m-d\TH:i',
            'discount'          => 'nullable|numeric|min:0|max:' . $request['price'],
            'voucher_code'      => 'nullable|string|max:255',
            'cover_path'        => 'nullable|image|max:2048',
            'thumbnail_path'    => 'nullable|image|max:2048',
        ]);
        // dd($validatedData['status']);

        // Store the data...
        DB::beginTransaction();
        try {
            // store the cover image
            if ($request->hasFile('cover_path')) {
                $coverPath = $request->file('cover_path')->store('package_cover', 'public');
                $validatedData['cover_path'] = $coverPath;
            }

            // store the thumbnail image
            if ($request->hasFile('thumbnail_path')) {
                $thumbnailPath = $request->file('thumbnail')->store('package_thumbnail', 'public');
                $validatedData['thumbnail_path'] = $thumbnailPath;
            }

            // Konversi format tanggal dan waktu
            if ($request->has('sale_start_at')) {
                $validatedData['sale_start_at'] = date('Y-m-d H:i:s', strtotime($validatedData['sale_start_at']));
            }

            if ($request->has('sale_end_at')) {
                $validatedData['sale_end_at'] = date('Y-m-d H:i:s', strtotime($validatedData['sale_end_at']));
            }

            // store the cover image
            if ($request->hasFile('cover_path')) {
                $coverPath = $request->file('cover_path')->store('package_cover', 'public');
                $validatedData['cover_path'] = $coverPath;
            }

            // store the thumbnail image
            if ($request->hasFile('thumbnail_path')) {
                $thumbnailPath = $request->file('thumbnail')->store('package_thumbnail', 'public');
                $validatedData['thumbnail_path'] = $thumbnailPath;
            }

            $validatedData['slug'] = Str::slug($validatedData['name']);
            $validatedData['status'] = $request->has('status') ? 1 : 0;

            $package->update($validatedData);

            DB::commit();

            return redirect()->route('dashboard.packages.index')->with('success', 'Package updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Package failed to update '. $e->getMessage()
                ]
            ]);
            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        // DB Transaction
        DB::beginTransaction();
        try {
            $package->delete();
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
