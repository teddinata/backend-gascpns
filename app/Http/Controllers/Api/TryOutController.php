<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseFormatter;
use App\Models\StudentAnswer;
use App\Models\Package;
use App\Models\PackageTryOut;
use App\Models\Tryout;
use App\Models\TryoutDetail;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\CourseAnswer;

class TryOutController extends Controller
{
    // tryout on sale without auth
    public function onSale()
    {
        $tryouts = Package::paginate(6);

        return ResponseFormatter::success($tryouts, 'Data paket tryout berhasil diambil');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua paket tryout dari user
        $myTryouts = $user->enrolledPackageTryouts()
            ->with(['packageTryOuts.course.category', 'packageTryOuts.course.questions'])
            ->get();

        foreach ($myTryouts as $tryout) {
            foreach ($tryout->packageTryOuts as $tryoutItem) {
                $course = $tryoutItem->course;
                $answeredQuestionsIds = StudentAnswer::where('user_id', $user->id)
                    ->whereIn('course_question_id', $course->questions->pluck('id'))
                    ->pluck('course_question_id')
                    ->toArray();

                foreach ($course->questions as $question) {
                    if (!in_array($question->id, $answeredQuestionsIds)) {
                        $tryoutItem->nextQuestion = $question;
                        break;
                    }
                }
            }
        }

        return ResponseFormatter::success($myTryouts, 'Data paket tryout berhasil diambil');
    }

    public function startTryout($packageId)
    {
        $user = auth()->user();

        try {
            DB::beginTransaction();

            // Cek apakah siswa sudah memulai tryout sebelumnya
            $existingTryout = Tryout::where('user_id', $user->id)
                ->where('package_id', $packageId)
                ->exists();

            if ($existingTryout) {
                return ResponseFormatter::error(null, 'Kamu sudah memulai tryout ini sebelumnya', 400);
            }

            // Membuat tryout baru
            $tryout = Tryout::create([
                'user_id' => $user->id,
                'package_id' => $packageId,
                'started_at' => now(),
                'created_by' => $user->id,
            ]);

            // Mengambil semua pertanyaan dalam paket tryout
            $package = Package::with('packageTryOuts.course.questions')->findOrFail($packageId);

            // Menyimpan semua pertanyaan ke dalam tabel tryout_details
            foreach ($package->packageTryOuts as $packageTryOut) {
                foreach ($packageTryOut->course->questions as $question) {
                    TryoutDetail::create([
                        'tryout_id' => $tryout->id,
                        'course_question_id' => $question->id, // Simpan ID pertanyaan
                        'answer' => null, // Jawaban awalnya kosong
                        'score' => 0, // Skor awalnya 0
                        'created_by' => $user->id,
                    ]);
                }
            }

            DB::commit();

            return ResponseFormatter::success($tryout, 'Tryout berhasil dimulai');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Tryout gagal dimulai', 500);
        }
    }

    public function navigation($tryoutId)
    {
        // dd($tryoutId);
        $tryout = Tryout::with('tryout_details')->findOrFail($tryoutId);
        $tryoutDetails = $tryout->tryoutDetails;

        return ResponseFormatter::success($tryout, 'Data tryout berhasil diambil');
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
    public function show($tryoutId, $questionNumber)
    {
        try {
            // Mengambil detail tryout beserta daftar soal dan jawaban
            $tryout = Tryout::with(['tryout_details.courseQuestion' => function ($query) {
                $query->with(['answers:id,course_question_id,answer']);
            }])->findOrFail($tryoutId);

            // Mencari detail dari soal yang diminta
            $questionDetail = $tryout->tryout_details->where('course_question_id', $questionNumber)->first();

            // Jika nomor soal tidak ditemukan
            if (!$questionDetail) {
                return ResponseFormatter::error(null, 'Nomor soal tidak ditemukan', 404);
            }

            // Menyiapkan data yang akan dikembalikan
            $questionData = [
                'id' => $questionDetail->id, // Nomor detail soal
                'tryout_id' => $tryout->id, // Nomor tryout
                'question_number' => $questionDetail->id, // Nomor soal
                'course_question_id' => $questionDetail->courseQuestion->id, // Nomor soal
                'question' => $questionDetail->courseQuestion->question, // Pertanyaan
                'image' => $questionDetail->courseQuestion->image, // Gambar pertanyaan
                'answers' => $questionDetail->courseQuestion->answers
            ];

            return ResponseFormatter::success($questionData, 'Data soal berhasil diambil');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Data soal gagal diambil', 500);
        }
    }

    public function answerQuestion(Request $request, $tryoutId, $questionId)
    {
        $user = Auth::user();

        // Cari detail tryout
        $tryoutDetail = TryoutDetail::where('tryout_id', $tryoutId)
            ->where('course_question_id', $questionId)
            ->first();

        if (!$tryoutDetail) {
            return ResponseFormatter::error(null, 'Soal tidak ditemukan dalam tryout ini', 404);
        }

        $answerId = $request->answer_id;
        $answer = CourseAnswer::find($answerId);
        if (!$answer) {
            return ResponseFormatter::error(null, 'Jawaban tidak ditemukan', 404);
        }

        // Perbarui jawaban jika sudah ada, jika tidak, buat jawaban baru
        $tryoutDetail->updateOrCreate(
            ['tryout_id' => $tryoutId, 'course_question_id' => $questionId],
            ['answer' => $answer->answer, 'score' => $answer->score, 'updated_by' => $user->id]
        );

        return ResponseFormatter::success(null, 'Jawaban berhasil disimpan');
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
