<?php

namespace App\Http\Controllers;

use App\Models\StudentAnswer;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\CourseAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StudentAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, Course $course, $question)
    {
        // store student answer
        $question_details = CourseQuestion::where('id', $question)->firstOrFail();

        $validated = $request->validate([
            'answer_id' => 'required|exists:course_answers,id',
        ]);

        DB::beginTransaction();

        try {
            $selectedAnswer = CourseAnswer::find($validated['answer_id']);

            // dd($selectedAnswer);

            if($selectedAnswer->course_question_id != $question){
                $error = ValidationException::withMessages([
                    'system_error' => ['System Error! Jawaban tidak sesuai'],
                ]);
                throw $error;
            }

            $existingAnswer = StudentAnswer::where('course_question_id', $question)
                ->where('user_id', Auth::user()->id)
                ->first();

            if($existingAnswer){
                $error = ValidationException::withMessages([
                    'system_error' => ['Kamu telah menjawab pertanyaan ini'],
                ]);
                throw $error;
            }

            // score
            $scoreAnswer = $selectedAnswer->score;
            // dd($question);

            // dd($request->all());
            StudentAnswer::create([
                'user_id' => Auth::user()->id,
                'course_question_id' => $question,
                'answer' => $scoreAnswer
            ]);
            DB::commit();

            // next question
            $nextQuestion = CourseQuestion::where('course_id', $course->id)
                ->where('id', '>', $question)
                ->orderBy('id', 'asc')
                ->first();

            if($nextQuestion){
                return redirect()->route('dashboard.learning.course', [
                    'course' => $course->id,
                    'question' => $nextQuestion->id
                ]);
            } else {
                return redirect()->route('dashboard.learning.finished.course', $course->id);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error = ValidationException::withMessages([
                'system_error' => [$e->getMessage()],
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentAnswer $studentAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentAnswer $studentAnswer)
    {
        //
    }
}
