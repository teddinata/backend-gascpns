<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use App\Models\LoginAttempt;

class LoginAttemptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, $next)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $next($request);
        }

        $email = $request->input('email');
        $loginAttempt = LoginAttempt::where('email', $email)->first();

        if (!$loginAttempt) {
            LoginAttempt::create([
                'email' => $email,
                'attempts' => 1,
                'last_attempt_at' => Carbon::now(),
            ]);
            return Redirect::back()->withErrors(['email' => 'Invalid email or password.']);
        }

        $loginAttempt->attempts++;
        $loginAttempt->last_attempt_at = Carbon::now();
        $loginAttempt->save();

        if ($loginAttempt->attempts >= 3) {
            LoginAttempt::where('email', $email)->update(['attempts' => 0, 'last_attempt_at' => null]);
            return Redirect::back()->withErrors(['email' => 'Your account has been temporarily locked due to too many failed login attempts. Please wait 1 minute and try again.']);
        }

        return Redirect::back()->withErrors(['email' => 'Invalid email or password.']);
    }
}
