<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;



class CourseQuestionController extends Controller
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
    public function create(Course $course)
    {
        // $students = $course->students()->orderBy('id', 'desc')->get();
        return view('admin.questions.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        // dd($request->all());
        // $validated = $request->validate([
        //     'question' => 'required|string',
        //     'answers'  => 'required|array',
        //     'answers.*' => 'required|string',
        //     'score.*'    => 'required|integer',
        // ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('question_images', 'public');
            }

            $question = $course->questions()->create([
                'question'      => $request->question,
                'image'         => $imagePath ?? null, // gunakan $imagePath yang sudah diperbaiki
                'explanation'   => $request->explanation,
                'score'         => $request->score,
            ]);

            foreach ($request->answers as $index => $answerText) {
                $score = $request->score[$index];
                $question->answers()->create([
                    'answer' => $answerText,
                    'score'  => $score,
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.courses.show', $course->id)->with('success', 'Pertanyaan dan jawaban berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pertanyaan dan jawaban gagal dibuat');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseQuestion $courseQuestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseQuestion $courseQuestion)
    {
        // get the course
        $course = $courseQuestion->course;
        // $students = $course->students()->orderBy('id', 'desc')->get();
        return view('admin.questions.edit', compact('course', 'courseQuestion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseQuestion $courseQuestion)
    {
        // update the course
        DB::beginTransaction();

        try {
            // request image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('question_images', 'public');
            }

            $courseQuestion->update([
                'question'      => $request->question,
                'image'         => $imagePath ?? $courseQuestion->image,
                'explanation'   => $request->explanation,
                'score'         => $request->score,
            ]);

            $courseQuestion->answers()->delete();

            foreach ($request->answers as $index => $answerText) {
                $score = $request->score[$index];
                $courseQuestion->answers()->create([
                    'answer' => $answerText,
                    'score'  => $score,
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.courses.show', $courseQuestion->course_id)->with('success', 'Pertanyaan dan jawaban berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Pertanyaan dan jawaban gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseQuestion $courseQuestion)
    {
        DB::beginTransaction();

        try {
            $courseQuestion->delete();
            DB::commit();
            return redirect()->route('dashboard.courses.show', $courseQuestion->course_id)->with('success', 'Question deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Question failed to delete');
        }
    }

    public function import(Request $request, Course $course)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        // DB::beginTransaction();

        // $file = $request->file('file');
        // Excel::import(new QuestionsImport($course), $file);

        // DB::commit();

        // return redirect()->route('dashboard.courses.show', $course->id)->with('success', 'Questions imported successfully.');

        // dd($request->file());
        DB::beginTransaction();

        try {
            $file = $request->file('file');
            Excel::import(new QuestionsImport($course), $file);

            DB::commit();
            return redirect()->route('dashboard.courses.show', $course->id)->with('success', 'Questions imported successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to import questions.');
        }
    }


    public function bulkDelete(Request $request)
    {
        // Validasi bahwa minimal satu pertanyaan dipilih
        $request->validate([
            'selected_questions' => 'required|array',
        ]);

        // Hapus pertanyaan yang dipilih
        foreach ($request->selected_questions as $questionId) {
            // Lakukan penghapusan pertanyaan
            // Misalnya:
            Question::findOrFail($questionId)->delete();
        }

        return redirect()->back()->with('success', 'Selected questions deleted successfully');
    }

}
