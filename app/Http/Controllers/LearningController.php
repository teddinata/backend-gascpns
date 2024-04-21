<?php

namespace App\Http\Controllers;

use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\CourseAnswer;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Validation\ValidationException;


class LearningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $myCourses = $user->courses()->with('category')->orderBy('id', 'desc')->paginate(5);

        foreach ($myCourses as $course) {
            $totalQuestionsCount = $course->questions()->count();

            $answeredQuestionsCount = StudentAnswer::where('user_id', $user->id)
                ->whereHas('question', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })->distinct()->count('course_question_id');

            if($answeredQuestionsCount < $totalQuestionsCount){
                $firstUnansweredQuestion = CourseQuestion::where('course_id', $course->id)
                    ->whereNotIn('id', function($query)use ($user) {
                        $query->select('course_question_id')->from('student_answers')->where('user_id', $user->id);
                    })
                    ->orderBy('id', 'asc')
                    ->first();

                $course->nextQuestionId = $firstUnansweredQuestion ? $firstUnansweredQuestion->id : null;
            } else {
                $course->nextQuestionId = null;
            }
        }

        return view('student.courses.index', compact('myCourses'));
    }

    /**
     * Show the view for learning.
     */
    public function learning(Course $course, $question)
    {
        $user = Auth::user();

        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();

        if(!$isEnrolled){
            return redirect()->route('dashboard.learning.index')->with('error', 'You are not enrolled in this course');
        }

        $currentQuestion = CourseQuestion::where('id', $question)
            ->where('course_id', $course->id)
            ->with('answers')
            ->firstOrFail();

        $nextQuestion = CourseQuestion::where('course_id', $course->id)
            ->where('id', '>', $question)
            ->whereNotIn('id', function($query)use ($user) {
                $query->select('course_question_id')->from('student_answers')->where('user_id', $user->id);
            })
            ->orderBy('id', 'asc')
            ->first();

        return view('student.courses.learning', [
            'course' => $course,
            'question' => $currentQuestion,
            'nextQuestion' => $nextQuestion
        ]);
    }

    /**
     * Show the view for learning finished.
     */
    public function learning_finished(Course $course)
    {
        $user = Auth::user();

        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();

        if(!$isEnrolled){
            return redirect()->route('dashboard.learning.index')->with('error', 'You are not enrolled in this course');
        }

        return view('student.courses.learning_finished', compact('course'));
    }

    /**
     * Show the view for learning rapport.
     */
    public function learning_rapport(Course $course)
    {
        $user = Auth::user();

        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();

        if(!$isEnrolled){
            return redirect()->route('dashboard.learning.index')->withErrors('error', 'You are not enrolled in this course');
        }

        $questions = $course->questions()->with('answers')->get();

        $totalQuestionsCount = $questions->count();

        $studentAnswers = StudentAnswer::with('question')
            ->where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->get();

        $answeredQuestionsCount = StudentAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->distinct()->count('course_question_id');

        $correctAnswersCount = StudentAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('answer', 5)->count();

        $wrongAnswersCount = StudentAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('answer', 0)->count();

        // count the score answers saat tidak ada jawaban benar atau salah, skor hanya ada antara 1-5
        $scoreAnswersCount = StudentAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('answer', '>', 0)->where('answer', '<', 6)->count();

        $date = StudentAnswer::where('user_id', $user->id)
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->orderBy('created_at', 'desc')->first();

        return view('student.courses.learning_rapport', compact(
            'course',
            'studentAnswers',
            'totalQuestionsCount',
            'answeredQuestionsCount',
            'correctAnswersCount',
            'wrongAnswersCount',
            'scoreAnswersCount',
            'date'
        ));
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
