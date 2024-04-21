<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use App\Models\Course;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;


class CourseStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        // student list
        $students = $course->students()->orderBy('id', 'desc')->get();
        $questions = $course->questions()->orderBy('id', 'desc')->get();
        $totalQuestions = $questions->count();

        foreach ($students as $student) {
            $studentAnswers = StudentAnswer::where('user_id', $student->user_id)
                ->whereHas('question', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })->get();

            $answerCount = $studentAnswers->count();
            $correctAnswerCount = $studentAnswers->where('answer', 5)->count();

            if ($answerCount == 0) {
                $student->status = 'Not Started';
            } elseif ($answerCount < $totalQuestions) {
                $student->status = 'In Progress';
            } elseif ($answerCount == $totalQuestions && $correctAnswerCount < $totalQuestions) {
                $student->status = 'Not Passed';
            } elseif ($answerCount == $totalQuestions && $correctAnswerCount == $totalQuestions) {
                $student->status = 'Passed';
            }
        }

        // dd($students);

        return view('admin.students.index', compact('students', 'course'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        $students = $course->students()->orderBy('id', 'desc')->get();
        return view('admin.students.add_students', compact('course', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        // add students to course


        $user = User::where('email', $request->email)->first();

        // check apakah email ditemukan di dalam sistem
        if(!$user){
            $error = ValidationException::withMessages([
                'email' => ['Email tidak ditemukan di dalam sistem'],
            ]);
            throw $error;
        }

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();

        if($isEnrolled){
            $error = ValidationException::withMessages([
                'system_error' => ['User sudah terdaftar di kelas ini'],
            ]);
            throw $error;
        }

        DB::beginTransaction();

        try {
            $course->students()->attach($user->id);
            DB::commit();
            return redirect()->route('dashboard.courses.course_students.create', $course->id)->with('success', 'Student added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseStudent $courseStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseStudent $courseStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseStudent $courseStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseStudent $courseStudent)
    {
        //
    }
}
