<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // user where roles with spatie is student
        // $students = User::where('role', function ($query) {
        //     $query->where('name', 'student');
        // })->paginate(10);
        $students = User::where('role', 'user')
            ->with('referrals', 'referrer')
            ->orderBy('created_at', 'desc')->paginate(15);
        $title = 'Delete Student!';
        $text = "Are you sure you want to delete?";

        return view('admin.students.all_students', compact('students'));
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
    public function show(User $student)
    {
        // student with referrals and referrer
        $student = User::with(['referrals.user', 'referrer.referredBy'])->find($student->id);
        // dd($student);
        return view('admin.students.show', compact('student'));
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
    public function destroy($id)
    {
        $student = User::find($id);

        DB::beginTransaction();

        try {
            $student->delete();
            DB::commit();
            return redirect()->route('dashboard.students.index')->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'error' => [
                    'Student failed to delete '. $e->getMessage()
                ]
            ]);
            throw $error;
        }
    }
}
