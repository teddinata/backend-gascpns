<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseFormatter;
use App\Models\StudentAnswer;
use App\Models\Package;
use App\Models\PackageTryOut;
use App\Models\TryOut;
use App\Models\TryOutDetail;
use App\Models\Course;
use App\Models\CourseQuestion;
use App\Models\CourseAnswer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class TryOutController extends Controller
{
    // tryout on sale without auth
    public function onSale()
    {
        $tryouts = Package::where('is_premium', true)->where('status', 1)->paginate(6);

        foreach ($tryouts as $tryout) {
            $tryout->cover_path = asset('storage/' . $tryout->cover_path);
        }

        return ResponseFormatter::success($tryouts, 'Data paket tryout berhasil diambil');
    }

    public function soalFavorite()
    {

        $user = Auth::user();

        if (!$user) {
            return ResponseFormatter::error(null, 'User not authenticated', 401);
        }

        // Mengambil semua paket tryout dan menghitung jumlah user yang sudah membeli setiap paket
        $tryouts = Package::where('is_premium', true)
            ->where('status', 1)
            ->withCount(['courseStudents as students_count'])
            ->paginate(6);

        // Olah data image dan cek apakah user telah membeli paket
        foreach ($tryouts as $tryout) {
            $tryout->cover_path = asset('storage/' . $tryout->cover_path);

            // Cek jumlah tryout yang sudah dibeli oleh user
            $enrolledPackageTryouts = $user->enrolledPackageTryouts()->pluck('package_tryout_id')->toArray();
            $tryout->is_enrolled = in_array($tryout->id, $enrolledPackageTryouts);

            // cek user yang login sudah membeli paket atau belum
            $tryout->is_enrolled = $user->enrolledPackageTryouts()
            ->where('packages.id', $tryout->id)
            ->exists();
        }

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
            ->whereHas('packageTryOuts', function ($query) {
                $query->where('is_premium', 1);
            })
            ->with(['packageTryOuts.course.category', 'packageTryOuts.course.questions'])
            ->get();

        // buat kondisi jika siswa belum membeli tryout
        // if ($myTryouts->isEmpty()) {
        //     return ResponseFormatter::error(null, 'Kamu belum membeli paket tryout', 200);
        // }

        // Ambil semua tryout yang dimulai oleh user
        $startedTryouts = [];
        $tryoutIds = $myTryouts->pluck('id');
        if ($tryoutIds->isNotEmpty()) {
            $startedTryouts = TryOut::whereIn('package_id', $tryoutIds)
                ->where('user_id', $user->id)
                ->pluck('package_id')
                ->toArray();
        }

        // Loop untuk memeriksa apakah user sudah memulai tryout
        foreach ($myTryouts as $tryout) {
            $tryout->cover_path = asset('storage/' . $tryout->cover_path);

            // hitung jumlah siswa yang sudah membeli paket
            $tryout->students_count = $tryout->courseStudents()->count();

            $tryout->is_started = in_array($tryout->id, $startedTryouts);

            $currentTryout = TryOut::where('user_id', $user->id)
                ->where('package_id', $tryout->id)
                ->whereNotNull('started_at')
                ->with('tryout_details')
                ->first();

            if ($currentTryout) {
                $tryout->current_tryout = $currentTryout;
                $tryout->next = $currentTryout->tryout_details->first()->id;
                $answeredQuestions = $currentTryout->tryout_details->whereNotNull('answer')->count();
                $unansweredQuestions = $currentTryout->tryout_details->whereNull('answer')->count();
                $totalQuestions = $currentTryout->tryout_details->count();

                $tryout->answered_questions = $answeredQuestions;
                $tryout->unanswered_questions = $unansweredQuestions;
                $tryout->total_questions = $totalQuestions;
            } else {
                $tryout->current_tryout = null;
                $tryout->next = null;
                $tryout->answered_questions = 0;
                $tryout->unanswered_questions = 0;
                $tryout->total_questions = 0;
            }

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


    // show detail package
    public function showDetail($packageId)
    {
        $user = Auth::user();

        // Ambil data paket tryout
        $tryout = Package::findOrFail($packageId);

        return ResponseFormatter::success($tryout, 'Data paket tryout berhasil diambil');
    }

    // show by slug
    public function showBySlug($slug)
    {
        $user = Auth::user();

        // Ambil data paket tryout
        $tryout = Package::where('slug', $slug)->first();

        // if package have image
        if ($tryout->cover_path) {
            $tryout->cover_path = asset('storage/' . $tryout->cover_path);
        }

        if ($tryout->thumbnail_path) {
            $tryout->thumbnail_path = asset('storage/' . $tryout->thumbnail_path);
        }

        if (!$tryout) {
            return ResponseFormatter::error(null, 'Data paket tryout tidak ditemukan', 404);
        }

        return ResponseFormatter::success($tryout, 'Data paket tryout berhasil diambil');
    }

    public function startTryout($packageId)
    {
        $user = auth()->user();

        try {
            DB::beginTransaction();

            // Cek apakah siswa sudah memulai tryout sebelumnya
            $existingTryout = TryOut::where('user_id', $user->id)
                ->where('package_id', $packageId)
                ->exists();

            if ($existingTryout) {
                return ResponseFormatter::error(null, 'Kamu sudah memulai tryout ini sebelumnya', 400);
            }

            // Mengambil semua pertanyaan dalam paket tryout
            $package = Package::with('packageTryOuts.course.questions')->findOrFail($packageId);

            // Mengambil durasi dari package
            $totalDuration = $package->total_duration; // pastikan ini dalam satuan menit

            // Membuat tryout baru
            $tryout = TryOut::create([
                'user_id' => $user->id,
                'package_id' => $packageId,
                'started_at' => now(),
                // finished at diisi ditambahkan 100 menit dari waktu sekarang
                'finished_at' => now()->addMinutes($totalDuration),
                'created_by' => $user->id,
                'status_pengerjaan' => 'sedang dikerjakan',
                'status' => 1,
            ]);

            // Menyimpan semua pertanyaan ke dalam tabel tryout_details
            foreach ($package->packageTryOuts as $packageTryOut) {
                foreach ($packageTryOut->course->questions as $question) {
                    TryOutDetail::create([
                        'tryout_id' => $tryout->id,
                        'course_question_id' => $question->id, // Simpan ID pertanyaan
                        'answer' => null, // Jawaban awalnya kosong
                        'score' => 0, // Skor awalnya 0
                        'created_by' => $user->id,
                    ]);
                }
            }

            DB::commit();
            // tambahkan variabel next dari id tryout details yang pertama untuk navigasi soal spertama
            $tryout->next = $tryout->tryout_details->first()->id;

            return response()->json([
                'status' => 'success',
                'message' => 'TryOut berhasil dimulai',
                'data' => $tryout,
                'next' => $tryout->next
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'TryOut gagal dimulai', 500);
        }
    }

    public function navigation($tryoutId)
    {
        // dd($tryoutId);
        $tryout = TryOut::with('tryout_details')->findOrFail($tryoutId);
        $tryoutDetails = $tryout->tryout_details;

        // Menambahkan nomor soal dan navigasi
        if ($tryoutDetails && $tryoutDetails->count() > 0) {
            // Menambahkan nomor soal dan navigasi
            foreach ($tryoutDetails as $index => $detail) {
                $detail->question_number = $index + 1;
                $detail->prev_question = $index > 0 ? $tryoutDetails[$index - 1]->course_question_id : null;
                $detail->next_question = $index < ($tryoutDetails->count() - 1) ? $tryoutDetails[$index + 1]->course_question_id : null;
                // hidden updated_by, created_at, updated_at
                $detail->makeHidden('updated_by', 'created_at', 'updated_at');
            }
        } else {
            // Jika $tryoutDetails null atau kosong, beri pesan yang sesuai
            return ResponseFormatter::error(null, 'No tryout details found', 404);
        }

        // exclude score from answer from tryout details
        $tryoutDetails->makeHidden('updated_by', 'created_at', 'updated_at');

        // total answered questions
        $answeredQuestions = $tryoutDetails->whereNotNull('answer')->count();
        $unansweredQuestions = $tryoutDetails->whereNull('answer')->count();

        $data = [
            'tryout_id' => $tryout->id,
            'user_id' => $tryout->user_id,
            'package_id' => $tryout->package_id,
            'started_at' => $tryout->started_at,
            'finished_at' => $tryout->finished_at,
            'total_questions' => $tryoutDetails->count(),
            'answered_questions' => $answeredQuestions,
            'unanswered_questions' => $unansweredQuestions,
            'tryout_details' => $tryoutDetails,
        ];

        return ResponseFormatter::success($data, 'Data navigasi tryout berhasil diambil');
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
    public function show($questionId)
    {
        try {
            // mengambil tryoutId dari questionId
            $tryoutId = TryOutDetail::where('id', $questionId)->first()->tryout_id;
            // Mengambil detail tryout beserta daftar soal dan jawaban
            $tryoutDetail = TryOutDetail::with(['courseQuestion.answers'])
                ->where('tryout_id', $tryoutId)
                ->findOrFail($questionId);

            // Jika detail tryout tidak ditemukan
            if (!$tryoutDetail) {
                return ResponseFormatter::error(null, 'Data tryout tidak ditemukan', 404);
            }

            // mengolah data image
            if ($tryoutDetail->courseQuestion->image) {
                $tryoutDetail->courseQuestion->image = asset('storage/' . $tryoutDetail->courseQuestion->image);
            }

            // select data tryout detail->courseQuestion->answers
            $tryoutDetail->courseQuestion->answers->makeHidden(['score', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by']);

            // total answered questions
            $answeredQuestions = $tryoutDetail->whereNotNull('answer')->where('tryout_id', $tryoutId)->count();
            $unansweredQuestions = $tryoutDetail->whereNull('answer')->where('tryout_id', $tryoutId)->count();
            $totalQuestions = $tryoutDetail->where('tryout_id', $tryoutId)->count();

            // next question
            $nextTryoutDetail = TryOutDetail::where('tryout_id', $tryoutDetail->tryout_id)
                ->where('id', '>', $tryoutDetail->id)
                ->first();

            // Jika nextTryoutDetail belum ditemukan pada tryout_id yang sama,
            // atau nextTryoutDetail merupakan nomor terakhir dari semua tryout,
            // maka atur next sebagai null
            if (!$nextTryoutDetail || $nextTryoutDetail->tryout_id !== $tryoutDetail->tryout_id) {
                $nextTryoutDetail = null;
            }

            $tryoutDetail->start_time = now();
            $tryoutDetail->save();

            // Menyiapkan data yang akan dikembalikan
            $questionData = [
                'id' => $tryoutDetail->id, // Nomor detail soal
                'tryout_id' => $tryoutDetail->tryout_id, // Nomor tryout
                'question_number' => $tryoutDetail->id, // Nomor soal
                'course_question_id' => $tryoutDetail->course_question_id, // Nomor soal
                'question' => $tryoutDetail->courseQuestion->question, // Pertanyaan
                'image' => $tryoutDetail->courseQuestion->image, // Gambar pertanyaan
                'course_answer_id' => $tryoutDetail->course_answer_id,
                'next' => $nextTryoutDetail ? $nextTryoutDetail->id : null,
                'answered_questions' => $answeredQuestions,
                'unanswered_questions' => $unansweredQuestions,
                'total_questions' => $totalQuestions,
                'answers' => $tryoutDetail->courseQuestion->answers,
            ];

            return ResponseFormatter::success($questionData, 'Data soal berhasil diambil');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Data soal gagal diambil', 500);
        }
    }

    // public function showoldmethod($tryoutId, $questionNumber)
    // {
    //     try {
    //         // Mengambil detail tryout beserta daftar soal dan jawaban
    //         $tryout = TryOut::with(['tryout_details.courseQuestion' => function ($query) {
    //             $query->with(['answers:id,course_question_id,answer']);
    //         }])->findOrFail($tryoutId);

    //         // Mencari detail dari soal yang diminta
    //         $questionDetail = $tryout->tryout_details->where('course_question_id', $questionNumber)->first();

    //         // Jika nomor soal tidak ditemukan
    //         if (!$questionDetail) {
    //             return ResponseFormatter::error(null, 'Nomor soal tidak ditemukan', 404);
    //         }

    //         // Menyiapkan data yang akan dikembalikan
    //         $questionData = [
    //             'id' => $questionDetail->id, // Nomor detail soal
    //             'tryout_id' => $tryout->id, // Nomor tryout
    //             'question_number' => $questionDetail->id, // Nomor soal
    //             'course_question_id' => $questionDetail->courseQuestion->id, // Nomor soal
    //             'question' => $questionDetail->courseQuestion->question, // Pertanyaan
    //             'image' => $questionDetail->courseQuestion->image, // Gambar pertanyaan
    //             'answers' => $questionDetail->courseQuestion->answers
    //         ];

    //         return ResponseFormatter::success($questionData, 'Data soal berhasil diambil');
    //     } catch (\Exception $e) {
    //         return ResponseFormatter::error($e->getMessage(), 'Data soal gagal diambil', 500);
    //     }
    // }

    public function answerQuestion(Request $request, $questionId)
    {
        $user = Auth::user();

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Cari detail tryout berdasarkan ID
            $tryoutDetail = TryOutDetail::with('tryout')
                ->findOrFail($questionId);

            if (!$tryoutDetail) {
                return ResponseFormatter::error(null, 'Soal tidak ditemukan dalam tryout ini', 404);
            }

            // Memastikan jawaban yang dikirim valid
            $answerId = $request->answer_id;
            $answer = CourseAnswer::findOrFail($answerId);
            if (!$answer) {
                return ResponseFormatter::error(null, 'Jawaban tidak ditemukan', 404);
            }

            // $nextTryoutDetail = $tryoutDetail->where('id', '>', $tryoutDetail->id)->first();

            // Jika tidak ada detail tryout berikutnya, set nilai $next menjadi null
            // $next = $nextTryoutDetail ? $nextTryoutDetail->id : null;

            $nextTryoutDetail = TryOutDetail::where('tryout_id', $tryoutDetail->tryout_id)
                ->where('id', '>', $tryoutDetail->id)
                ->first();

            // Jika nextTryoutDetail belum ditemukan pada tryout_id yang sama,
            // atau nextTryoutDetail merupakan nomor terakhir dari semua tryout,
            // maka atur next sebagai null
            if (!$nextTryoutDetail || $nextTryoutDetail->tryout_id !== $tryoutDetail->tryout_id) {
                $nextTryoutDetail = null;
            }

            // Memperbarui nilai next
            $tryoutDetail->next = $nextTryoutDetail ? $nextTryoutDetail->id : null;

            // pengecekan apakah try out sudah selesai atau belum, jika sudah selesai maka tidak bisa menjawab soal
            if ($tryoutDetail->tryout->finished_at < now()) {
                return ResponseFormatter::error(null, 'Waktu tryout sudah habis', 400);
            }

            $answerId = intval($request->answer_id);
            $data = [
                'answer' => $answer->answer,
                'score' => $answer->score,
                'updated_by' => $user->id,
                'course_answer_id' => $answerId, // Menambahkan answer_id ke dalam data yang akan disimpan/diperbarui
                'end_time' => now() // Menambahkan waktu selesai menjawab soal
            ];

            // Memperbarui jawaban atau membuat jawaban baru jika belum ada
            $tryoutDetail->updateOrCreate(
                ['tryout_id' => $tryoutDetail->tryout_id, 'course_question_id' => $tryoutDetail->course_question_id],
                $data
            );

            // Commit transaksi database jika tidak ada kesalahan
            DB::commit();

            return ResponseFormatter::success($tryoutDetail, 'Jawaban berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollback();
            return ResponseFormatter::error(null, 'Terjadi kesalahan saat memproses jawaban', 500);
        }
    }

    public function answerQuestionWithoutDB(Request $request, $questionId)
    {
        $user = Auth::user();

        // Cari detail tryout berdasarkan ID
        $tryoutDetail = TryOutDetail::with('tryout')
            ->findOrFail($questionId);

        if (!$tryoutDetail) {
            return ResponseFormatter::error(null, 'Soal tidak ditemukan dalam tryout ini', 404);
        }

        // Memastikan jawaban yang dikirim valid
        $answerId = $request->answer_id;
        $answer = CourseAnswer::findOrFail($answerId);
        if (!$answer) {
            return ResponseFormatter::error(null, 'Jawaban tidak ditemukan', 404);
        }

        $nextTryoutDetail = $tryoutDetail->where('id', '>', $tryoutDetail->id)->first();

        // Jika tidak ada detail tryout berikutnya, set nilai $next menjadi null
        $next = $nextTryoutDetail ? $nextTryoutDetail->id : null;

        // Memperbarui nilai next
        $tryoutDetail->next = $next;

        // pengecekan apakah try out sudah selesai atau belum, jika sudah selesai maka tidak bisa menjawab soal
        if ($tryoutDetail->tryout->finished_at < now()) {
            return ResponseFormatter::error(null, 'Waktu tryout sudah habis', 400);
        }

        $answerId = intval($request->answer_id);
        $data = [
            'answer' => $answer->answer,
            'score' => $answer->score,
            'updated_by' => $user->id,
            'course_answer_id' => $answerId // Menambahkan answer_id ke dalam data yang akan disimpan/diperbarui
        ];

        // Memperbarui jawaban atau membuat jawaban baru jika belum ada
        $tryoutDetail->updateOrCreate(
            ['tryout_id' => $tryoutDetail->tryout_id, 'course_question_id' => $tryoutDetail->course_question_id],
            $data
        );

        return ResponseFormatter::success($tryoutDetail, 'Jawaban berhasil disimpan');
    }


    public function answerQuestionOldMethod(Request $request, $tryoutId, $questionId)
    {
        $user = Auth::user();

        // Cari detail tryout
        $tryoutDetail = TryOutDetail::where('tryout_id', $tryoutId)
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
            ['answer' => $answer->answer, 'score' => $answer->score, 'updated_by' => $user->id],
            ['answer_id' => $answer->id]
        );

        return ResponseFormatter::success(null, 'Jawaban berhasil disimpan');
    }

    public function finishTryout($tryoutId)
    {
        try {
            // Mulai transaksi database
            DB::beginTransaction();

            $tryout = TryOut::findOrFail($tryoutId);

            if (!$tryout) {
                return ResponseFormatter::error(null, 'TryOut tidak ditemukan', 404);
            }

            // check apakah semua pertanyaan pada tryout sudah dijawab atau belum
            $answeredQuestions = $tryout->tryout_details->whereNotNull('answer')->count();
            $totalQuestions = $tryout->tryout_details->count();

            if ($answeredQuestions < $totalQuestions) {
                // jika waktu tryout belum habis, maka siswa tidak bisa menyelesaikan tryout
                if ($tryout->finished_at > now()) {
                    return ResponseFormatter::error(null, 'Masih ada soal yang belum dijawab', 400);
                } else {
                    // jika waktu tryout sudah habis, maka siswa bisa menyelesaikan tryout
                    $tryout->update([
                        'status_pengerjaan' => 'sudah dikerjakan',
                        'status' => 2,
                        'finish_time' => now(),
                    ]);

                    // Commit transaksi database jika tidak ada kesalahan
                    DB::commit();

                    return ResponseFormatter::success($tryout, 'Mohon maaf, waktu tryout sudah habis. TryOut berhasil selesai, silahkan cek hasil tryout kamu');
                }
            }

            // check apakah waktu tryout sudah habis atau belum
            if ($tryout->finished_at < now()) {
                return ResponseFormatter::error(null, 'Waktu tryout sudah habis', 400);
            }

            $tryout->update([
                'status_pengerjaan' => 'sudah dikerjakan',
                'status' => 2,
                'finish_time' => now(),
            ]);

            // Commit transaksi database jika tidak ada kesalahan
            DB::commit();

            return ResponseFormatter::success($tryout, 'TryOut berhasil selesai');
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollback();
            return ResponseFormatter::error(null, 'Terjadi kesalahan saat menyelesaikan tryout', 500);
        }
    }
    // function finish tryout
    public function finishTryoutWithoutDB($tryoutId)
    {
        $tryout = TryOut::findOrFail($tryoutId);

        if (!$tryout) {
            return ResponseFormatter::error(null, 'TryOut tidak ditemukan', 404);
        }

        // check apakah semua pertanyaan pada tryout sudah dijawab atau belum
        $answeredQuestions = $tryout->tryout_details->whereNotNull('answer')->count();
        $totalQuestions = $tryout->tryout_details->count();

        if ($answeredQuestions < $totalQuestions) {
            return ResponseFormatter::error(null, 'Masih ada soal yang belum dijawab', 400);
        }

        // check apakah waktu tryout sudah habis atau belum
        if ($tryout->finished_at < now()) {
            return ResponseFormatter::error(null, 'Waktu tryout sudah habis', 400);
        }

        $tryout->update([
            'status_pengerjaan' => 'sudah dikerjakan',
            'status' => 2,
            'finish_time' => now(),
        ]);

        return ResponseFormatter::success($tryout, 'TryOut berhasil selesai');
    }

    // show student summary tryout
    public function summary($tryoutId)
    {
        $user = Auth::user();

        // Cari tryout berdasarkan ID dan user_id untuk memastikan kepemilikan
        $tryout = TryOut::where('status', 2)
            ->where('user_id', $user->id) // Pastikan tryout milik user yang sedang login
            ->with(['tryout_details.courseQuestion.course', 'package'])
            ->findOrFail($tryoutId);

        // Pengecekan apakah tryout ditemukan dan milik user yang sedang login
        if (!$tryout) {
            return ResponseFormatter::error(null, 'TryOut tidak ditemukan', 404);
        }

        $passingGrade = $tryout->tryout_details->first()->courseQuestion->course->passing_grade;

        $categories = $tryout->tryout_details->map(function ($detail) {
            return (object) [
                'id' => $detail->courseQuestion->course->category->id,
                'name' => $detail->courseQuestion->course->category->name,
                'passing_grade' => $detail->courseQuestion->course->passing_grade,
                // hitung score yang diperoleh siswa pada kategori soal
                // Add other properties as needed
            ];
        })->unique()->values()->toArray();

        // hitung score berdasarkan kategori soal
        foreach ($categories as $category) {
            $category->score = $tryout->tryout_details->where('courseQuestion.course.category_id', $category->id)->sum('score');
        }

        // next try out
        $tryout->next = $tryout->tryout_details->first()->id;

        if (!$tryout) {
            return ResponseFormatter::error(null, 'TryOut tidak ditemukan', 404);
        }

        $answeredQuestions = $tryout->tryout_details->whereNotNull('answer')->count();
        $unansweredQuestions = $tryout->tryout_details->whereNull('answer')->count();
        $totalQuestions = $tryout->tryout_details->count();

        // hitung score total
        $totalScore = $tryout->tryout_details->sum('score');
        // hitung maximum score dari kolom score yang bernilai 5 dikali jumlah soal
        $maxScore = $totalQuestions * 5;


        $data = [
            'tryout_id' => $tryout->id,
            'user_id' => $tryout->user_id,
            'package_id' => $tryout->package_id,
            'started_at' => $tryout->started_at,
            'finished_at' => $tryout->finished_at,
            'finish_time' => $tryout->finish_time,
            'total_questions' => $totalQuestions,
            'total_score' => $totalScore,
            'max_score' => $maxScore,
            'answered_questions' => $answeredQuestions,
            'unanswered_questions' => $unansweredQuestions,
            'next' => $tryout->next,
            'categories' => $categories,
            'package' => $tryout->package,
            'tryout_details' => $tryout->tryout_details,
        ];

        return ResponseFormatter::success($data, 'Data ringkasan tryout berhasil diambil');
    }

    public function showSummary($questionId)
    {
        try {
            // mengambil tryoutId dari questionId
            $tryoutId = TryOut::where('status', 2)
                ->with(['tryout_details.courseQuestion.course', 'package'])
                ->whereHas('tryout_details', function ($query) use ($questionId) {
                    $query->where('id', $questionId);
                })
                ->first()->id;
            // Mengambil detail tryout beserta daftar soal dan jawaban
            $tryoutDetail = TryOutDetail::with(['courseQuestion','courseQuestion.answers'])
                ->where('tryout_id', $tryoutId)
                ->findOrFail($questionId);
            // dd($tryoutDetail);

            // Jika detail tryout tidak ditemukan
            if (!$tryoutDetail) {
                return ResponseFormatter::error(null, 'Data tryout tidak ditemukan', 404);
            }

            // mengolah data image
            if ($tryoutDetail->courseQuestion->image) {
                $tryoutDetail->courseQuestion->image = asset('storage/' . $tryoutDetail->courseQuestion->image);
            }

            // select data tryout detail->courseQuestion->answers
            $tryoutDetail->courseQuestion->answers->makeHidden(['created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by']);

            // total answered questions
            $answeredQuestions = $tryoutDetail->whereNotNull('answer')->where('tryout_id', $tryoutId)->count();
            $unansweredQuestions = $tryoutDetail->whereNull('answer')->where('tryout_id', $tryoutId)->count();
            $totalQuestions = $tryoutDetail->where('tryout_id', $tryoutId)->count();

            // count is correct answer use score === 5
            $correctAnswers = $tryoutDetail->where('score', 5)->where('tryout_id', $tryoutId)->count();
            // false answer use score 1, 2, 3, 4;
            $falseAnswers = $tryoutDetail->where('score', '!=', 5)->whereNotNull('answer')->where('tryout_id', $tryoutId)->count();
            // blank answer
            $blankAnswers = $tryoutDetail->whereNull('answer')->where('tryout_id', $tryoutId)->count();

            // next question
            $nextTryoutDetail = TryOutDetail::where('id', '>', $tryoutDetail->id)->first();

            // Menyiapkan data yang akan dikembalikan
            $questionData = [
                'id' => $tryoutDetail->id, // Nomor detail soal
                'tryout_id' => $tryoutDetail->tryout_id, // Nomor tryout
                'question_number' => $tryoutDetail->id, // Nomor soal
                'course_question_id' => $tryoutDetail->course_question_id, // Nomor soal
                'question' => $tryoutDetail->courseQuestion->question, // Pertanyaan
                'image' => $tryoutDetail->courseQuestion->image, // Gambar pertanyaan
                'course_answer_id' => $tryoutDetail->course_answer_id,
                'next' => $nextTryoutDetail ? $nextTryoutDetail->id : null,
                'answered_questions' => $answeredQuestions,
                'unanswered_questions' => $unansweredQuestions,
                'total_questions' => $totalQuestions,
                'is_correct' => $correctAnswers,
                'is_false' => $falseAnswers,
                'is_blank' => $blankAnswers,
                'question_category' => $tryoutDetail->courseQuestion->course->category->name,
                'questions' => $tryoutDetail->courseQuestion->makeHidden(['answers', 'course']),
                'answers' => $tryoutDetail->courseQuestion->answers,
                'start_time' => $tryoutDetail->start_time,
                'end_time' => $tryoutDetail->end_time,
            ];

            return ResponseFormatter::success($questionData, 'Data soal berhasil diambil');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Data soal gagal diambil', 500);
        }
    }

    // show ranking for all user tryout by package
    public function rankingsByPackage(Request $request)
    {
        // Validasi input
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        // Ambil ID paket tryout dari permintaan
        $packageId = $request->input('package_id');

        // Ambil parameter pencarian nama jika ada
        $searchName = $request->input('search_name', '');

        // Ambil semua pengguna yang mengikuti tryout dari paket tryout yang dipilih dan sesuai dengan nama pencarian
        $usersQuery = User::whereHas('tryouts', function ($query) use ($packageId) {
            $query->where('package_id', $packageId)->where('status', 2); // Pastikan tryout sudah selesai
        });

        if (!empty($searchName)) {
            $usersQuery->where('name', 'like', '%' . $searchName . '%');
        }

        // Dapatkan pengguna dengan pagination
        $users = $usersQuery->paginate(10);

        // Inisialisasi array untuk menyimpan data peringkat
        $rankings = [];

        $lulus = 0; // Counter untuk tryout yang lulus

        // Loop melalui setiap pengguna
        foreach ($users as $user) {
            // Ambil tryout pengguna dari paket tryout yang dipilih
            $tryout = $user->tryouts()->where('package_id', $packageId)->where('status', 2)->first();

            if (!$tryout) {
                continue; // Skip jika tidak ada tryout yang sesuai
            }

            $totalScore = 0;
            foreach ($tryout->tryout_details as $detail) {
                $totalScore += $detail->score;
            }

            // Hitung skor berdasarkan kategori soal
            $twkScore = $tryout->tryout_details->where('courseQuestion.course.category.name', 'TWK')->sum('score');
            $tiuScore = $tryout->tryout_details->where('courseQuestion.course.category.name', 'TIU')->sum('score');
            $tkpScore = $tryout->tryout_details->where('courseQuestion.course.category.name', 'TKP')->sum('score');
            // Menentukan apakah tryout lulus atau tidak
            // $twkScore = $tryout->tryout_details->where('courseQuestion.course.category_id', 1)->sum('score') >= 85;
            // $tiuScore = $tryout->tryout_details->where('courseQuestion.course.category_id', 2)->sum('score') >= 65;
            // $tkpScore = $tryout->tryout_details->where('courseQuestion.course.category_id', 3)->sum('score') >= 166;

            if ($twkScore && $tiuScore && $tkpScore && $totalScore >= 311) {
                $lulus++;
            }

            // Hitung total skor
            // $totalScore = $twkScore + $tiuScore + $tkpScore;

            // Tambahkan data peringkat ke dalam array rankings
            $rankings[] = [
                'rank' => count($rankings) + 1,
                'name' => $user->name,
                'provinsi' => $user->provinsi, // Ganti dengan atribut yang sesuai
                'twk' => $twkScore,
                'tiu' => $tiuScore,
                'tkp' => $tkpScore,
                'total' => $totalScore,
                'keterangan' => $totalScore >= 311 ? 'Lulus' : 'Tidak Lulus', // Ganti dengan kriteria kelulusan yang sesuai
            ];
        }

        // Urutkan peringkat berdasarkan skor total
        usort($rankings, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Tambahkan nomor peringkat setelah diurutkan
        foreach ($rankings as $index => $ranking) {
            $rankings[$index]['rank'] = $index + 1;
        }

        // Paginate the rankings array manually
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = array_slice($rankings, ($currentPage - 1) * $perPage, $perPage);
        $paginatedRankings = new LengthAwarePaginator($currentItems, count($rankings), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        // Kembalikan data peringkat dalam bentuk respons JSON
        return ResponseFormatter::success($paginatedRankings, 'Data peringkat berhasil diambil');
    }

    // endpoint all package tryout
    public function allPackageTryout()
    {
        $tryouts = Package::with('packageTryOuts.course.category')->get();

        return ResponseFormatter::success($tryouts, 'Data paket tryout berhasil diambil');
    }


    public function raport()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil semua tryout yang diikuti user
        $tryouts = TryOut::with('tryout_details.courseQuestion.course.category', 'package')
            ->where('user_id', $user->id)
            ->get();

        // Pisahkan tryout yang belum dikerjakan dan sudah dikerjakan
        $completedTryouts = $tryouts->where('status', 2);
        $notCompletedTryouts = $tryouts->where('status', '<>', 2);

        // Menghitung statistik
        $totalTryouts = $tryouts->count();
        $completedTryoutsCount = $completedTryouts->count();
        // $passingRate = $completedTryoutsCount / ($totalTryouts > 0 ? $totalTryouts : 1) * 100;

        $lulus = 0; // Counter untuk tryout yang lulus

        foreach ($tryouts as $tryout) {
            $totalScore = 0;
            foreach ($tryout->tryout_details as $detail) {
                $totalScore += $detail->score;
            }

            // Menentukan apakah tryout lulus atau tidak
            $twkScore = $tryout->tryout_details->where('courseQuestion.course.category_id', 1)->sum('score');
            $tiuScore = $tryout->tryout_details->where('courseQuestion.course.category_id', 2)->sum('score');
            $tkpScore = $tryout->tryout_details->where('courseQuestion.course.category_id', 3)->sum('score');

            $twkPass = $twkScore >= 85;
            $tiuPass = $tiuScore >= 65;
            $tkpPass = $tkpScore >= 166;

            if ($twkPass && $tiuPass && $tkpPass && $totalScore >= 311) {
                $lulus++;
            }
        }

        // Hitung persentase kelulusan
        $passingRate = ($totalTryouts > 0) ? ($lulus / $totalTryouts) * 100 : 0;

        // Persiapkan array untuk menyimpan data tryout beserta nilai kategori
        $tryoutData = [];

        // Iterasi melalui tryout yang sudah dikerjakan
        foreach ($completedTryouts as $tryout) {
            $categories = $tryout->tryout_details->map(function ($detail) {
                return (object) [
                    'id' => $detail->courseQuestion->course->category->id,
                    'name' => $detail->courseQuestion->course->category->name,
                    'passing_grade' => $detail->courseQuestion->course->passing_grade,
                    // tambahkan properti lain jika diperlukan
                ];
            })->unique()->values()->toArray();

            // Hitung nilai dari setiap kategori soal
            foreach ($categories as $category) {
                $category->score = $tryout->tryout_details
                    ->where('courseQuestion.course.category_id', $category->id)
                    ->sum('score');
            }

            // Persiapkan data tryout
            $tryoutData[] = [
                'tryout_id' => $tryout->id,
                'user_id' => $tryout->user_id,
                'package_id' => $tryout->package_id,
                'started_at' => $tryout->started_at,
                'finished_at' => $tryout->finished_at,
                'finish_time' => $tryout->finish_time,
                'categories' => $categories,
                'package' => $tryout->package,
            ];
        }

        // Menyiapkan response data
        $responseData = [
            'totalTryouts' => $totalTryouts,
            'completedTryouts' => $completedTryoutsCount,
            'passingRate' => $passingRate,
            'tryouts' => $tryoutData,
        ];

        // Kembalikan data dalam bentuk respons JSON
        return ResponseFormatter::success($responseData, 'Data raport berhasil diambil');
    }

    // proses tryout
    // Fungsi tambahan untuk memproses tryout
    protected function processTryout($tryout, $user, $startedTryouts)
    {
        // $tryout->cover_path = asset('storage/' . $tryout->cover_path);

        // hitung jumlah siswa yang sudah membeli paket
        $tryout->students_count = $tryout->courseStudents()->count();


        $currentTryout = TryOut::where('user_id', $user->id)
            ->where('package_id', $tryout->id)
            ->whereNotNull('started_at')
            // ->with('tryout_details')
            ->first();

        $tryout->is_started = in_array($tryout->id, $startedTryouts);

        if ($currentTryout) {
            $tryout->current_tryout = $currentTryout;
            $tryout->next = $currentTryout->tryout_details->first()->id;
            $answeredQuestions = $currentTryout->tryout_details->whereNotNull('answer')->count();
            $unansweredQuestions = $currentTryout->tryout_details->whereNull('answer')->count();
            $totalQuestions = $currentTryout->tryout_details->count();

            $tryout->answered_questions = $answeredQuestions;
            $tryout->unanswered_questions = $unansweredQuestions;
            $tryout->total_questions = $totalQuestions;
        } else {
            $tryout->current_tryout = null;
            $tryout->next = null;
            $tryout->answered_questions = 0;
            $tryout->unanswered_questions = 0;
            $tryout->total_questions = 0;
        }

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

    // bantu saya buatkan function latihan soal / tryout gratis sama seperti function favorite
    public function freePackage()
    {
        $user = Auth::user();

        if (!$user) {
            return ResponseFormatter::error(null, 'User not authenticated', 401);
        }

        // Ambil semua paket tryout gratis dan menghitung jumlah user yang sudah membeli setiap paket
        $tryouts = Package::where('is_premium', false)
            ->withCount(['courseStudents as students_count'])
            ->get();

        // Olah data image dan cek apakah user telah membeli paket
        foreach ($tryouts as $tryout) {
            if ($tryout->cover_path) {
                $tryout->cover_path = asset('storage/' . $tryout->cover_path);
            }

            // Cek apakah user sudah membeli paket
            $tryout->is_enrolled = $user->enrolledPackageTryouts()
                ->where('package_tryout_id', $tryout->id)
                ->exists();

            // Jika user sudah membeli paket, tambahkan informasi tambahan
            if ($tryout->is_enrolled) {
                $this->processTryout($tryout, $user, []);
            }
        }

        return ResponseFormatter::success($tryouts, 'Data paket tryout gratis berhasil diambil');
    }

    public function claimFreePackage(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return ResponseFormatter::error(null, 'User not authenticated', 401);
        }

        $packageId = $request->input('package_id');

        $package = Package::where('id', $packageId)->where('is_premium', false)->first();

        if (!$package) {
            return ResponseFormatter::error(null, 'Paket tidak ditemukan atau bukan paket gratis', 404);
        }

        // Cek apakah user sudah mengklaim paket ini
        $isEnrolled = $user->enrolledPackageTryouts()->where('package_tryout_id', $packageId)->exists();

        if ($isEnrolled) {
            return ResponseFormatter::error(null, 'Anda sudah mengklaim paket ini', 400);
        }

        // Klaim paket untuk user
        $user->enrolledPackageTryouts()->attach($packageId, ['created_by' => $user->id]);
        // $student->packages()->attach($package->id, ['created_by' => '1 ']);

        return ResponseFormatter::success(null, 'Paket berhasil diklaim');
    }

    // rank by tryout id
    public function getRankByTryoutId(Request $request, $tryoutId)
    {
        // Ambil tryout berdasarkan ID
        $tryout = TryOut::with('user', 'tryout_details.courseQuestion.course.category')->findOrFail($tryoutId);

        // Ambil ID paket tryout
        $packageId = $tryout->package_id;

        // Ambil semua pengguna yang mengikuti tryout dari paket tryout yang sama dan sudah selesai
        $usersQuery = User::whereHas('tryouts', function ($query) use ($packageId) {
            $query->where('package_id', $packageId)->where('status', 2); // Pastikan tryout sudah selesai
        });

        // Dapatkan semua pengguna
        $users = $usersQuery->get();

        // Inisialisasi array untuk menyimpan data peringkat
        $rankings = [];

        // Loop melalui setiap pengguna
        foreach ($users as $user) {
            // Ambil tryout pengguna dari paket tryout yang dipilih
            $userTryout = $user->tryouts()->where('package_id', $packageId)->where('status', 2)->first();

            if (!$userTryout) {
                continue; // Skip jika tidak ada tryout yang sesuai
            }

            $totalScore = 0;
            foreach ($userTryout->tryout_details as $detail) {
                $totalScore += $detail->score;
            }

            // Hitung skor berdasarkan kategori soal
            $twkScore = $userTryout->tryout_details->where('courseQuestion.course.category.name', 'TWK')->sum('score');
            $tiuScore = $userTryout->tryout_details->where('courseQuestion.course.category.name', 'TIU')->sum('score');
            $tkpScore = $userTryout->tryout_details->where('courseQuestion.course.category.name', 'TKP')->sum('score');

            // Hitung total skor dan tentukan apakah tryout lulus atau tidak
            if ($twkScore >= 85 && $tiuScore >= 65 && $tkpScore >= 166 && $totalScore >= 311) {
                $lulus = true;
            } else {
                $lulus = false;
            }

            // Tambahkan data peringkat ke dalam array rankings
            $rankings[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'provinsi' => $user->provinsi, // Ganti dengan atribut yang sesuai
                'twk' => $twkScore,
                'tiu' => $tiuScore,
                'tkp' => $tkpScore,
                'total' => $totalScore,
                'keterangan' => $lulus ? 'Lulus' : 'Tidak Lulus', // Ganti dengan kriteria kelulusan yang sesuai
            ];
        }

        // Urutkan peringkat berdasarkan skor total
        usort($rankings, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // Tambahkan nomor peringkat setelah diurutkan
        foreach ($rankings as $index => $ranking) {
            $rankings[$index]['rank'] = $index + 1;
        }

        // Cari peringkat pengguna yang sesuai dengan tryoutId yang diberikan
        $userRank = collect($rankings)->firstWhere('user_id', $tryout->user_id);

        // Kembalikan data peringkat pengguna dalam bentuk respons JSON
        return ResponseFormatter::success($userRank, 'Data peringkat berhasil diambil');
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
