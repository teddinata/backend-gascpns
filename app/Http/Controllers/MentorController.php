<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // user where roles with spatie is student
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->orWhere('role', 'admin')->paginate(10);

        $title = 'Delete Mentor!';
        $text = "Are you sure you want to delete?";

        return view('admin.user.all_users', compact('students'));
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
        return view('admin.user.show', compact('student'));
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
            return redirect()->route('dashboard.students.index')->with('success', 'User deleted successfully');
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
