<?php

namespace App\Http\Controllers;

use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\CourseStudent;
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

        // Ambil semua paket tryout dari user
        // $myTryouts = $user->packages()
        //     ->with(['packageTryOuts.course.category', 'packageTryOuts.course.questions'])
        //     ->paginate(10);
        $myTryouts = $user->enrolledPackageTryouts()
        ->with(['packageTryOuts.course.category', 'packageTryOuts.course.questions'])
            ->paginate(10);
        // dd($myTryouts);

        foreach ($myTryouts as $tryout) {
            foreach ($tryout->packageTryOuts as $tryoutItem) {
                $course = $tryoutItem->course;
                $answeredQuestionsIds = StudentAnswer::where('user_id', $user->id)
                    ->whereIn('course_question_id', $course->questions->pluck('id'))
                    ->pluck('course_question_id')
                    ->toArray();

                foreach ($course->questions as $question) {
                    if (!in_array($question->id, $answeredQuestionsIds)) {
                        $question->nextQuestion = $question;
                        break;
                    }
                }
            }
        }

        // dd count question
        // dd($totalQuestionsCount);
        // dd($myTryouts);
        // dd($myTryouts);

        // foreach ($myTryouts as $tryout) {
        //     $totalQuestionsCount = $tryout->course->questions()->count();

        //     $answeredQuestionsCount = StudentAnswer::where('user_id', $user->id)
        //         ->whereHas('question', function ($query) use ($tryout) {
        //             $query->where('package_tryout_id', $tryout->id);
        //         })->distinct()->count('course_question_id');

        //     if ($answeredQuestionsCount < $totalQuestionsCount) {
        //         $firstUnansweredQuestion = CourseQuestion::where('course_id', $tryout->course_id)
        //             ->whereNotIn('id', function ($query) use ($user) {
        //                 $query->select('course_question_id')->from('student_answers')->where('user_id', $user->id);
        //             })
        //             ->orderBy('id', 'asc')
        //             ->first();

        //         $tryout->nextQuestionId = $firstUnansweredQuestion ? $firstUnansweredQuestion->id : null;
        //     } else {
        //         $tryout->nextQuestionId = null;
        //     }
        // }

        return view('student.courses.index', compact('myTryouts'));
    }

    /**
     * Show the view for learning.
     */
    public function learning($package, $questionId)
    {
        // dd($packageId, $questionId);
        $user = Auth::user();

        $course = Course::findOrFail($package)->load('questions.answers');

        $myTryouts = $user->enrolledPackageTryouts()
            ->with(['packageTryOuts.course.category', 'packageTryOuts.course.questions.answers'])
            ->paginate(10);

        $isEnrolled =  $myTryouts->contains('id', $package);
        // dd($isEnrolled);

        if(!$isEnrolled){
            return redirect()->route('dashboard.learning.index')->with('error', 'You are not enrolled in this course');
        }

        $question = CourseQuestion::findOrFail($questionId)->load('answers');


        $answeredQuestionsIds = StudentAnswer::where('user_id', $user->id)
            ->pluck('course_question_id')
            ->toArray();

        $packageQuestions = $question->course->questions;

        $nextQuestionId = null;
        $hasUnansweredQuestion = false;

        foreach ($packageQuestions as $index => $q) {
            if (!in_array($q->id, $answeredQuestionsIds)) {
                $hasUnansweredQuestion = true;
                $nextQuestionId = $q->id;
                break;
            }
        }
        // dd([
        //     'course' => $course,
        //     'question' => $question,
        //     'nextQuestionId' => $nextQuestionId,
        //     'hasUnansweredQuestion' => $hasUnansweredQuestion
        // ]);
        dd($nextQuestionId);

        return view('student.courses.learning', [
            'course' => $course,
            'question' => $question,
            'nextQuestionId' => $nextQuestionId,
            'hasUnansweredQuestion' => $hasUnansweredQuestion
        ]);
    }

    public function learning_old(Course $course, $question)
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
