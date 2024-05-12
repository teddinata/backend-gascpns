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
