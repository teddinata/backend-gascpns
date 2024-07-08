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
use App\Services\NotificationService;
use App\Mail\AccessGranted;
use App\Mail\SuccessEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\Voucher;

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
            'email' => 'required|array',
            'email.*' => 'required|email',
        ]);

        // check validasi request jika gagal
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validation Error',
                'error' => $validator->errors(),
            ], 'Validation Error', 422);
        }

        // Periksa apakah jumlah email sama dengan quantity
        if (count($request->email) != $request->quantity) {
            return ResponseFormatter::error([
                'message' => 'The number of emails must match the quantity',
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
        $invoiceId = 'GAS' . date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $fixedPrice = $package->discount ?? $package->price;

        DB::beginTransaction();
        try {
            $transactions = [];

            foreach ($request->email as $email) {
                $student = User::where('email', $email)->first();

                if (!$student) {
                    return ResponseFormatter::error([
                        'message' => 'Email ' . $email . ' belum terdaftar sebagai user',
                    ], 'Email ' . $email . ' belum terdaftar sebagai user', 422);
                }

                if ($student->packages()->where('package_tryout_id', $package->id)->exists()) {
                    return ResponseFormatter::error([
                        'message' => 'Paket sudah pernah dibeli oleh user ' . $email,
                    ], 'Paket sudah pernah dibeli oleh user ' . $email, 422);
                }


                $transaction = Transaction::create([
                    'invoice_code' => $invoiceCode,
                    'invoice_id' => $invoiceId,
                    'package_id' => $package->id,
                    'student_id' => $student->id,
                    'student_id_transaction' => auth()->id(),
                    'quantity' => 1,
                    'total_amount' => $fixedPrice,
                    'original_price' => $package->price,
                    'discount_price' => $package->discount ?? 0,
                    'payment_status' => 'PENDING',
                    // External ID akan diatur nanti
                ]);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'package_name' => $package->name,
                    'package_price' => $package->price, // Menggunakan price di sini
                    'quantity' => 1, // Hanya satu paket per transaksi
                    'price' => $package->price, // Menggunakan price di sini
                    'original_price' => $package->price,
                ]);

                $transactions[] = $transaction;
            }

            DB::commit();
            return ResponseFormatter::success(['transactions' => $transactions], 'Transactions created successfully');
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
        $validator = Validator::make($request->all(), [
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
            'payment_method' => 'required|in:WALLET',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Validation Error',
                'error' => $validator->errors(),
            ], 'Validation Error', 422);
        }

        $transactions = Transaction::whereIn('id', $request->transaction_ids)->get();
        $totalAmount = $transactions->sum('total_amount');
        $user = Auth::user();

        if ($user->wallet_balance < $totalAmount) {
            return ResponseFormatter::error([
                'message' => 'Saldo tidak mencukupi',
            ], 'Saldo tidak mencukupi', 422);
        }

        try {
            DB::beginTransaction();

            foreach ($transactions as $trx) {
                $trx->payment_method = $request->payment_method;
                $trx->payment_status = 'PAID';
                $trx->save();

                $student = $trx->student;
                $student->packages()->attach($trx->package_id, ['created_by' => $user->id]);

                NotificationService::sendNotification($student->id, 'Akses Paket', 'Anda telah mendapatkan akses ke paket ' . $trx->package->name . '.', 'https://app.gascpns.com/member/my-tryout');
                Mail::to($student->email)->send(new AccessGranted($student, $trx));
            }

            $user->wallet_balance -= $totalAmount;
            $user->save();

            NotificationService::sendNotification($user->id, 'Pembelian Paket Berhasil', 'Pembelian paket menggunakan saldo berhasil. Saldo Anda saat ini Rp' . $user->wallet_balance, 'https://app.gascpns.com/member/my-tryout');
            Mail::to($user->email)->send(new SuccessEmail($user, $transactions->first()));

            DB::commit();
            return ResponseFormatter::success($transactions, 'Transaction paid successfully');
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

    // vouchers list
    public function vouchers()
    {
        $vouchers = Voucher::where('is_active', 1)
            ->where('valid_from', '<=', Carbon::now())
            ->where('valid_to', '>=', Carbon::now())
            ->get();

        return ResponseFormatter::success($vouchers, 'Vouchers list');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('is_active', 1)
            ->where('valid_from', '<=', Carbon::now())
            ->where('valid_to', '>=', Carbon::now())
            ->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher tidak valid atau sudah kadaluarsa'], 422);
        }

        $usedVoucherCount = $voucher->transactions()
            ->whereIn('payment_status', ['PENDING', 'UNPAID', 'SUCCESS'])
            ->count();

        if ($voucher->usage_limit && $usedVoucherCount >= $voucher->usage_limit) {
            return response()->json(['error' => 'Voucher sudah mencapai batas penggunaan'], 422);
        }

        $transactions = Transaction::whereIn('id', $request->transaction_ids)->get();

        // Calculate the total amount based on original_price or discount_price
        $totalAmount = $transactions->reduce(function ($acc, $trx) {
            return $acc + ($trx->discount_price ?? $trx->original_price);
        }, 0);

        if ($voucher->min_purchase && $totalAmount < $voucher->min_purchase) {
            return response()->json(['error' => 'Total pembelian tidak memenuhi syarat minimum penggunaan voucher'], 422);
        }

        $discountAmount = 0;

        if ($voucher->discount_type == 'percentage') {
            $discountAmount = ($voucher->discount_amount / 100) * $totalAmount;
        } else if ($voucher->discount_type == 'fixed') {
            $discountAmount = $voucher->discount_amount;
        }

        if ($voucher->max_discount && $discountAmount > $voucher->max_discount) {
            $discountAmount = $voucher->max_discount;
        }

        $finalAmount = $totalAmount - $discountAmount;

        try {
            DB::beginTransaction();

            foreach ($transactions as $trx) {
                // Calculate the proportion of discount for each transaction
                $originalOrDiscountPrice = $trx->discount_price ?? $trx->original_price;
                $proportion = $originalOrDiscountPrice / $totalAmount;
                $trxDiscountAmount = $discountAmount * $proportion;

                // Update transaction
                $trx->discount_amount = $trxDiscountAmount;
                $trx->voucher_id = $voucher->id;
                $trx->voucher_code = $voucher->code;
                $trx->total_amount = $originalOrDiscountPrice - $trxDiscountAmount;
                $trx->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to apply voucher'], 500);
        }
    }

    public function getAppliedVoucher(Request $request)
    {
        $request->validate([
            'transaction_ids' => 'required|array',
            'transaction_ids.*' => 'exists:transactions,id',
        ]);

        $transactions = Transaction::whereIn('id', $request->transaction_ids)->get();

        $appliedVouchers = $transactions->map(function ($trx) {
            return [
                'id' => $trx->voucher_id,
                'code' => $trx->voucher_code,
                'discount_amount' => $trx->discount_amount,
            ];
        })->unique('id')->values();

        return response()->json([
            'success' => true,
            'vouchers' => $appliedVouchers,
        ]);
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
