<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
// change depedency password
// use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use App\Models\Referral;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['nullable', 'string', 'max:255', 'unique:users'],
                'birthdate' => ['required', 'date'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required', 'string', 'min:8'],
                'referral_code' => 'nullable|exists:users,referral_code',
            ]);

            // Check email existence
            if (User::where('email', $request->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => ['Email is already taken. Please choose another.']
                ]);
            }

            // Check referral code (using query builder)
            $referrer = null;
            if ($request->referral_code) {
                $referrer = DB::table('users')->where('referral_code', $request->referral_code)->first();
                if (!$referrer) {
                    throw ValidationException::withMessages([
                        'referral_code' => ['Referral code is invalid.']
                    ]);
                }
            }

            // Generate OTP
            $otp = mt_rand(1000, 9999);

            // Create or update user (using firstOrNew)
            $user = User::firstOrNew(['email' => $request->email]);
            $user->fill([
                'name' => $request->name,
                'username' => $request->username,
                'birthdate' => $request->birthdate,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'otp' => $otp,
                'otp_expired_at' => now()->addMinutes(15),
            ]);
            $user->save();

            // Send email verification (using queue)
            Mail::to($request->email)->send(new EmailVerification($otp, $user));

            // Handle referral (using database transaction)
            if ($referrer) {
                DB::transaction(function () use ($user, $referrer) {
                    Referral::create([
                        'user_id' => $user->id,
                        'referred_by' => $referrer->id,
                        'referral_code' => $user->referral_code,
                    ]);

                    DB::table('users')->where('id', $referrer->id)->increment('wallet_balance', 10000);
                    $user->increment('wallet_balance', 10000);
                });
            }

            // Create token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'User registered successfully');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return ResponseFormatter::error([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 'Authentication Failed', 422);
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
                'otp' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || $user->otp !== $request->otp) {
                return ResponseFormatter::error([
                    'message' => 'OTP yang kamu masukkan salah'
                ], 'Invalid OTP', 400);
            }

            if (now()->isAfter($user->otp_expired_at)) {
                return ResponseFormatter::error([
                    'message' => 'OTP sudah kadaluarsa. Silakan minta OTP yang baru.'
                ], 'OTP Expired', 400);
            }

            // OTP valid, tandai pengguna sebagai terverifikasi (misalnya, set kolom 'email_verified_at')
            $user->markEmailAsVerified();

            // Hapus OTP setelah berhasil diverifikasi
            $user->otp = null;
            $user->otp_expired_at = null;
            $user->save();

            return ResponseFormatter::success([
                'user' => $user
            ], 'Selamat! Akun kamu berhasil diverifikasi. Sekarang akun kamu sudah aktif. Selamat belajar!');
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 'Verification Failed', 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            $user = User::where('email', $request->email)->first();

            // Correctly handle otp_expired_at
            $otpExpiredAt = $user->otp_expired_at ? Carbon::parse($user->otp_expired_at) : null;

            // Check if OTP was recently sent (optional)
            if ($otpExpiredAt && now()->lt($otpExpiredAt->addMinutes(1))) {
                return ResponseFormatter::error([
                    'message' => 'Terlalu banyak permintaan. Mohon tunggu sebelum mengirimkan OTP lagi.'
                ], 'Too Many Requests', 429);
            }

            // Generate a new OTP
            $newOtp = mt_rand(1000, 9999);
            $user->otp = $newOtp;
            $user->otp_expired_at = now()->addMinutes(15);
            $user->save();

            // Send the new OTP via email
            Mail::to($user->email)->send(new EmailVerification($newOtp, $user));

            return ResponseFormatter::success($user, 'OTP resent successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 'Resend Failed', 500);
        }
    }


    // end point check user
    public function fetch(Request $request)
    {
        // check user with role admin
        $user = User::where('role', 'user')->get();
        return ResponseFormatter::success(
            $user,'Data user berhasil diambil');
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email_or_username' => 'required|string',
                'password' => 'required|string',
            ]);

            $field = filter_var($request->email_or_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $credentials = [
                $field => $request->email_or_username,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Periksa apakah email sudah terverifikasi
                if (! $user->hasVerifiedEmail()) {
                    // Generate new OTP
                    $otp = mt_rand(1000, 9999);
                    $user->otp = $otp;
                    $user->otp_expired_at = now()->addMinutes(15);
                    $user->save();

                    // Send verification email (using queue)
                    Mail::to($user->email)->send(new EmailVerification($otp, $user));

                    return ResponseFormatter::error([
                        'message' => 'Email kamu belum terverifikasi nih. Silakan cek email kamu untuk verifikasi ya. Kami sudah mengirimkan email verifikasi ulang.'
                    ], 'Unverified Email', 400, $user->email);
                }


                $tokenResult = $user->createToken('authToken')->plainTextToken;
                $user->last_login = now();
                $user->save();

                return ResponseFormatter::success([
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ], 'User logged in successfully');
            }

            throw ValidationException::withMessages([
                'auth' => ['The provided credentials are incorrect.'],
            ]);

        } catch (ValidationException $e) {
            // Handling validation errors
            $errors = $e->errors();
            return ResponseFormatter::error([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 'Authentication Failed', 422);

        } catch (\Exception $e) {
            // Handling other exceptions
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return ResponseFormatter::success(null, 'User logged out successfully');

        } catch (\Exception $e) {
            // Handling other exceptions
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 'Logout failed', 500);
        }
    }

    // user login
    public function profile(Request $request)
    {
        $user = Auth::user();

        // if user have avatar
        if ($user->avatar) {
            $user->avatar = url('storage/' . $user->avatar);
        } else {
            $user->avatar = url('https://ui-avatars.com/api/?name=' . $user->name);
        }
        return ResponseFormatter::success($user, 'Data profile user berhasil diambil');
    }
}
