<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageTryOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TryOutStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Package $package)
    {
        $package_tryouts = $package->packageTryOuts()->orderBy('id', 'desc')->with('students', 'package', 'course')->get();
        $student_lists = $package->students()->orderBy('id', 'desc')->get();
        // dd($student_lists);
        // dd($package_tryouts);
        $students = collect();
        $totalQuestions = 0;
        foreach ($package_tryouts as $package_tryout) {
            $students = $students->merge($package_tryout->students);
            $totalQuestions += $package_tryout->course->questions()->count();
        }

        // foreach ($students as $student) {
        //     $questions = $student->course->questions()->orderBy('id', 'desc')->get();
        //     $totalQuestions = $questions->count();

        //     $studentAnswers = StudentAnswer::where('user_id', $student->user_id)
        //         ->whereIn('question_id', $questions->pluck('id'))
        //         ->get();

        //     $answerCount = $studentAnswers->count();
        //     $correctAnswerCount = $studentAnswers->where('answer', 5)->count();

        //     if ($answerCount == 0) {
        //         $student->status = 'Not Started';
        //     } elseif ($answerCount < $totalQuestions) {
        //         $student->status = 'In Progress';
        //     } elseif ($answerCount == $totalQuestions && $correctAnswerCount < $totalQuestions) {
        //         $student->status = 'Not Passed';
        //     } elseif ($answerCount == $totalQuestions && $correctAnswerCount == $totalQuestions) {
        //         $student->status = 'Passed';
        //     }
        // }

        $title = 'Delete Course!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        // dd($students);
        return view('admin.tryout-students.index', compact('package_tryouts', 'package', 'students', 'totalQuestions', 'student_lists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Package $package)
    {
        // dd($package->packageTryOuts()->orderBy('id', 'desc')->get());
        $package_tryouts = $package->packageTryOuts()->orderBy('id', 'desc')->get();

        // Mengumpulkan semua siswa dari semua package tryouts
        $students = collect();
        $totalQuestions = 0;

        foreach ($package_tryouts as $package_tryout) {
            $students = $students->merge($package_tryout->students);
            $totalQuestions += $package_tryout->course->questions()->count();
        }

        return view('admin.tryout-students.add_students', compact('package', 'students', 'package_tryouts', 'totalQuestions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Package $package)
    {
        $user = User::where('email', $request->email)->first();

        // Check if email is found in the system
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan di dalam sistem'],
            ]);
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $isEnrolled = $package->students()->where('user_id', $user->id)->exists();

        if ($isEnrolled) {
            throw ValidationException::withMessages([
                'system_error' => ['User sudah terdaftar di kelas ini'],
            ]);
        }

        DB::beginTransaction();

        try {
            $package->students()->attach($user->id, [
                'created_by' => auth()->id(),
            ]);
            DB::commit();
            return redirect()->route('dashboard.tryouts.students.create', $package->id)->with('success', 'Student added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => $e->getMessage(),
            ]);
        }
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
    public function destroy(Package $package, User $student)
    {
        // DB::beginTransaction();

        // try {
        //     $package->students()->detach($student->id);
        //     DB::commit();
        //     return redirect()->route('dashboard.tryouts.students.index', $package->id)->with('success', 'Student removed successfully');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->route('dashboard.tryouts.students.index', $package->id)->with('error', 'Failed to remove student');
        // }
    }

    public function delete(Package $package, User $student)
    {
        DB::beginTransaction();

        try {
            $package->students()->detach($student->id);
            DB::commit();
            return redirect()->route('dashboard.tryouts.students.index', $package->id)->with('success', 'Student removed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dashboard.tryouts.students.index', $package->id)->with('error', 'Failed to remove student');
        }
    }
}
