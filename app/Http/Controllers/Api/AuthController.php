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
                'password' => ['required', 'string', 'min:8', 'confirmed', new Password],
                'password_confirmation' => ['required', 'string', 'min:8'],
                'referral_code' => 'nullable|exists:users,referral_code',
            ]);

            // Check email existence
            if (User::where('email', $request->email)->exists()) {
                throw ValidationException::withMessages([
                    'email' => ['Email is already taken. Please choose another.']
                ]);
            }

            // check referral code
            $referrer = null;
            if ($request->referral_code) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if (!$referrer) {
                    throw ValidationException::withMessages([
                        'referral_code' => ['Referral code is invalid.']
                    ]);
                }
            }

            // Creating or getting a new user
            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'username' => $request->name,
                    'birthdate' => $request->birthdate,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'role' => 'user',
                ]
            );

            // Assigning referral code to the user
            if ($referrer) {
                Referral::create([
                    'user_id' => $user->id,
                    'referred_by' => $referrer->id,
                    'referral_code' => $user->referral_code,
                ]);

                // Tambah saldo pada referrer 10.000
                $referrer->increment('wallet_balance', 10000);
                $user->increment('wallet_balance', 10000); // Tambah saldo pada pengguna baru
            }

            // Creating a token for the newly created user
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Returning user and token data to the client
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'User registered successfully');

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

    // end point check user
    public function fetch(Request $request)
    {
        // check user with role admin
        $user = User::where('roles', 'admin')->get();
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
}
