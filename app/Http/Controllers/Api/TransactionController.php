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


use Illuminate\Support\Carbon;
use PSpell\Config;

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

        // check user available menggunakan email
        $student = User::where('email', $request->email)->first();

        // check user
        if (!$student) {
            return ResponseFormatter::error([
                'message' => 'Student not found',
            ], 'Student not found', 422);
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
            $student_id_transaction = ($request->email === auth()->user()->email) ? $student_id : null;

            $fixedPrice = $package->discount ?? $package->price;

            $transaction = Transaction::create([
                'invoice_code'              => $invoiceCode,
                'package_id'                => $package->id,
                'student_id'                => $student->id,
                'student_id_transaction'    => $student_id_transaction, // 'student_id_transaction' => $request->student_id_transaction,
                'quantity'                  => $request->quantity,
                'total_amount'              => $fixedPrice * $request->quantity,
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
