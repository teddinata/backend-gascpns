<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Package;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseFormatter;
use App\Http\Service\XenditService;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use Xendit\QRCode;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;
use PSpell\Config;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    protected $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

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
    public function store(Request $request)
    {
        // validate request data
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'quantity' => 'required|integer',
            'email' => 'required',
            'details' => 'nullable|array',
        ]);

        // check validasi request jika gagal
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validation Error',
                'error' => $validator->errors(),
            ], 'Validation Error', 422);
        }

        // get package
        $package = Package::find($request->package_id);

        // check package
        if (!$package) {
            return response()->json(['message' => 'Package not found'], 422);
        }

        // Mendapatkan tanggal hari ini dalam format YYYYMMDD
        $date = date('Ymd');

        // Membuat string acak dengan panjang 6 karakter uppercase
        $randomString = Str::upper(Str::random(6));

        // Menggabungkan semua komponen untuk membuat invoice code
        $invoiceCode = 'GAS-' . $date . $randomString;

        // invoice id GAS2024052600001
        // generate random number
        $invoiceId = 'GAS' . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        // check user available menggunakan email
        $student = User::where('email', $request->email)->first();

        // check user
        if (!$student) {
            return ResponseFormatter::error([
                'message' => 'Email Siswa tidak ditemukan',
            ], 'Email Siswa tidak ditemukan', 422);
        }

        // pengecekan request email dengan quantity apakah sama atau belum
        // Periksa apakah jumlah email sama dengan quantity
        $emailCount = is_array($request->email) ? count($request->email) : 1;
        if ($request->quantity != $emailCount) {
            return ResponseFormatter::error([
                'message' => 'Kuantitas email tidak sama dengan quantity',
            ], 'Kuantitas email tidak sama dengan quantity', 422);
        }

        // check tryout yang sudah dibeli user
        if ($student->packages()->where('package_tryout_id', $package->id)->exists()) {
            return ResponseFormatter::error([
                'message' => 'Tidak dapat menambahkan paket! Paket ini sudah pernah dibeli.',
            ], 'Tidak dapat menambahkan paket! Paket ini sudah pernah dibeli.', 422);
        }


        try {
            // create transaction
            DB::beginTransaction();

            // Tetapkan nilai student_id berdasarkan email
            $student_id = $student->id;

            // Tetapkan nilai student_id_transaction berdasarkan kondisi
            $student_id_transaction = ($request->email === auth()->user()->email) ? $student_id : auth()->id();

            $fixedPrice = $package->discount ?? $package->price;

            $transaction = Transaction::create([
                'invoice_code'              => $invoiceCode,
                'invoice_id'                => $invoiceId,
                'package_id'                => $package->id,
                'student_id'                => $student->id,
                'student_id_transaction'    => $student_id_transaction, // 'student_id_transaction' => $request->student_id_transaction,
                'quantity'                  => $request->quantity,
                'total_amount'              => $fixedPrice * $request->quantity,
                'original_price'            => $package->price,
                'discount_price'            => $package->discount ?? 0,
                'payment_status'            => 'PENDING',
            ]);

            $transactionDetails = [];
            foreach ($request->email as $email) {
                // Cari pengguna berdasarkan email
                $student = User::where('email', $email)->first();

                // Pastikan pengguna ditemukan
                if ($student) {
                    // Cek apakah pengguna sudah memiliki paket ini
                    if (!$student->packages()->where('package_tryout_id', $package->id)->exists()) {
                        // create transaction


                        // Menambahkan paket ke pengguna dengan nilai 'created_by' yang sesuai
                        // $student->packages()->attach($package->id, ['created_by' => auth()->id()]);

                        // Membuat transaction detail
                        TransactionDetail::create([
                            'transaction_id' => $transaction->id,
                            'package_name' => $package->name,
                            'package_price' => $fixedPrice,
                            'quantity' => 1, // Hanya satu paket per transaksi
                            'price' => $fixedPrice,
                            'original_price' => $package->price,
                        ]);
                    }
                } else {
                    // Jika pengguna tidak ditemukan, mungkin lakukan penanganan sesuai kebutuhan Anda
                    // tampilkan pesan error
                    return ResponseFormatter::error([
                        'message' => 'Siswa dengan email ' . $email . ' tidak ditemukan',
                    ], 'Siswa dengan email ' . $email . ' tidak ditemukan', 422);
                }
            }

            // $student->packages()->attach($package->id, ['created_by' => auth()->id()]);

            TransactionDetail::insert($transactionDetails);


            DB::commit();

            return ResponseFormatter::success($transaction, 'Transaction Success');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Transaction Failed');
        }
    }

    // show transaction detail by id
    public function show(string $id)
    {
        $transaction = Transaction::with('details', 'package')->find($id);

        // if image found in package
        if ($transaction->package->cover_path) {
            $transaction->package->cover_path = asset('storage/' . $transaction->package->cover_path);
        }

        $paymentExpired = Carbon::parse($transaction->payment_expired);
        $currentTime = Carbon::now();

        // check jika ada transaction yang belum success dan payment expired yang sudah lewat dari waktu sekarang maka ubah status menjadi EXPIRED
        if ($transaction->payment_status !== 'PAID' && $transaction->payment_status !== 'CANCELLED' && $currentTime->gt($paymentExpired)) {
            $transaction->payment_status = 'EXPIRED';
            $transaction->save(); // Simpan perubahan status
        }

        if (!$transaction) {
            return ResponseFormatter::error([
                'message' => 'Transaction not found',
            ], 'Transaction not found', 404);
        }

        return ResponseFormatter::success($transaction, 'Transaction detail');
    }

    /**
     * Cancel the specified transaction.
     */
    public function cancel(string $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return ResponseFormatter::error([
                'message' => 'Transaction not found',
            ], 'Transaction not found', 404);
        }

        if ($transaction->payment_status !== 'UNPAID') {
            return ResponseFormatter::error([
                'message' => 'Tidak dapat membatalkan transaksi yang sudah dibayar atau sudah dibatalkan',
            ], 'Invalid Transaction Status', 422);
        }

        try {
            // transaction expired time carbon format timestamp
            $transaction->payment_status = 'CANCELLED';
            $transaction->payment_expired = Carbon::now();
            $transaction->payment_response = 'Transaction cancelled by user';
            $transaction->payment_number = 'Transaction cancelled by user';
            $transaction->save();

            return ResponseFormatter::success($transaction, 'Transaction cancelled successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaction Cancellation Failed');
        }
    }


    // transaction payment method menggunakan saldo user yang sudah login dan membeli paket tryout yang diinginkan user tersebut
    public function saldoTransaction(Request $request)
    {
        // validate request data
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'required|in:WALLET',
        ]);

        // check validasi request jika gagal
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validation Error',
                'error' => $validator->errors(),
            ], 'Validation Error', 422);
        }

        // get transaction
        $transaction = Transaction::findOrFail($request->transaction_id);

        // check transaction
        if (!$transaction) {
            return ResponseFormatter::error([
                'message' => 'Transaction not found',
            ], 'Transaction not found', 404);
        }

        // check user
        // if ($transaction->student_id != auth()->id()) {
        //     return ResponseFormatter::error([
        //         'message' => 'Unauthorized',
        //     ], 'Unauthorized', 401);
        // }

        // check transaction status
        if ($transaction->payment_status === 'PAID') {
            return ResponseFormatter::error([
                'message' => 'Transaction already paid',
            ], 'Transaction already paid', 422);
        }

        // check user balance
        if (auth()->user()->wallet_balance < $transaction->total_amount) {
            return ResponseFormatter::error([
                'message' => 'Saldo tidak mencukupi',
            ], 'Saldo tidak mencukupi', 422);
        }

        // student
        $student = User::find($transaction->student_id);

        try {
            DB::beginTransaction();

            // update transaction
            $transaction->payment_method = $request->payment_method;
            $transaction->payment_status = 'PAID';
            $transaction->save();

            // update user balance
            $user = Auth::user();
            $user->wallet_balance -= $transaction->total_amount;
            $user->save();

            // update course to student
            $student->packages()->attach($transaction->package_id, ['created_by' => auth()->id()]);

            DB::commit();

            return ResponseFormatter::success($transaction, 'Transaction paid successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 'Transaction payment failed');
        }
    }

    // show transaction history for user
    public function history(Request $request)
    {
        $transactions = Transaction::with('details', 'package', 'student', 'studentTransaction')
            // ->where('student_id', auth()->id())
            ->where('student_id_transaction', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // if cover path found in package
        $transactions->getCollection()->each(function ($transaction) {
            if ($transaction->package && $transaction->package->cover_path) {
                // Check if cover_path is already a full URL
                if (!preg_match('/^https?:\/\//', $transaction->package->cover_path)) {
                    $transaction->package->cover_path = url('storage/' . $transaction->package->cover_path);
                }
            }
        });

        // if payment expired is less than current time then change status to EXPIRED
        $transactions->getCollection()->each(function ($transaction) {
            $paymentExpired = Carbon::parse($transaction->payment_expired)->setTimezone('Asia/Jakarta');
            $currentTime = Carbon::now('Asia/Jakarta');

            if ($transaction->payment_status !== 'PAID' && $transaction->payment_status !== 'CANCELLED' && $currentTime->gt($paymentExpired)) {
                // Hanya jika status belum dibayar dan sudah terlambat, ubah status menjadi EXPIRED
                $transaction->payment_status = 'EXPIRED';
                $transaction->save(); // Simpan perubahan status
            }
        });

        return ResponseFormatter::success($transactions, 'Transaction history');
    }

    // show transaction history detail for user
    public function historyDetail(string $id)
    {
        $transaction = Transaction::with('details', 'package', 'student', 'studentTransaction')
            // ->where('student_id', auth()->id())
            ->where('student_id_transaction', auth()->id())
            ->find($id);

        if (!$transaction) {
            return ResponseFormatter::error([
                'message' => 'Transaction not found',
            ], 'Transaction not found', 404);
        }

        return ResponseFormatter::success($transaction, 'Transaction detail');
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
