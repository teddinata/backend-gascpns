<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\LoginAttempt;
use Carbon\Carbon;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    // {
        // $request->authenticate();

        // $request->user()->update([
        //     'last_login' => now(),
        // ]);

        // $request->session()->regenerate();

        // return redirect()->intended(route('dashboard', absolute: false));

    // }
    {
        $credentials = $request->only('email', 'password');

        // Check login attempts and handle account locking
        $loginAttempt = LoginAttempt::where('email', $credentials['email'])->first();
        if ($loginAttempt && $loginAttempt->attempts >= 2) {
            $secondsSinceLastAttempt = Carbon::parse($loginAttempt->last_attempt_at)->diffInSeconds(now());
            if ($secondsSinceLastAttempt < 180) { // Change to 3 minutes (180 seconds)
                $waitTime = 180 - $secondsSinceLastAttempt;
                return redirect()->back()->withErrors(['email' => 'Akun Anda telah terkunci selama 3 menit karena terlalu banyak percobaan login yang gagal. Silakan tunggu ' . $waitTime . ' detik dan coba lagi.']);
            } else {
                $loginAttempt->attempts = 0;
                $loginAttempt->last_attempt_at = null;
                $loginAttempt->save();
            }
        }

        // Authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            if ($loginAttempt) {
                $loginAttempt->delete(); // Delete login attempt record upon successful login
            }

            $request->user()->update([
                    'last_login' => now(),
                ]);

            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        // Increment login attempts on failed login
        if ($loginAttempt) {
            $loginAttempt->incrementLoginAttempts();
            $loginAttempt->last_attempt_at = now(); // Ensure last_attempt_at is updated on every failed attempt
            $loginAttempt->save();
        } else {
            $loginAttempt = LoginAttempt::create([
                'email' => $credentials['email'],
                'attempts' => 1,
                'last_attempt_at' => now(),
            ]);
        }

        // jika percobaan telah mencapai 3x, maka kunci akun
        // if ($loginAttempt->attempts >= 3) {
        //     $loginAttempt->update(['last_attempt_at' => now()]);
        //     return redirect()->back()->withErrors(['email' => 'Akun Anda telah terkunci selama 3 menit karena terlalu banyak percobaan login yang gagal. Silakan tunggu beberapa saat dan coba lagi.']);
        // }

        // jika percobaan telah mencapai 2x maka beri peringatan untuk percobaan 1 kali lagi
        if ($loginAttempt->attempts == 2) {
            return redirect()->back()->withErrors(['email' => 'Anda memiliki 1 percobaan login tersisa sebelum akun Anda terkunci sementara.']);
        }

        // Return error message on failed login
        return redirect()->back()->withErrors(['email' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami. Periksa kembali email dan kata sandi Anda.']);
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
