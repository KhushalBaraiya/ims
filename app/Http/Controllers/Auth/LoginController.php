<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LanguageController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use App\Models\Currency;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        // ── Find the user by email ──────────────────────────────────────────
        $user = User::where('email', $request->email)->first();

        // ── Check if account is locked ────────────────────────────────────
        if ($user && $user->locked_until) {
            if (now()->lt($user->locked_until)) {
                // Still locked — store exact unlock timestamp in session for countdown
                session(['lockout_until_ts' => $user->locked_until->timestamp]);
                $remaining = max(1, (int) ceil(now()->diffInSeconds($user->locked_until) / 60));
                throw ValidationException::withMessages([
                    'email' => "LOCKED:{$user->locked_until->timestamp}",
                ]);
            } else {
                // Lock period expired — auto-reset
                $user->update([
                    'failed_login_attempts' => 0,
                    'locked_until'          => null,
                ]);
                session()->forget('lockout_until_ts');
            }
        }

        // ── Attempt login ─────────────────────────────────────────────────
        if (!Auth::attempt($credentials, $remember)) {

            // Get max attempts from settings (cached)
            $maxAttempts = (int) Cache::remember('setting_max_login_attempts', 300, function () {
                return Setting::where('key', 'max_login_attempts')->value('value') ?? 5;
            });
            $lockoutMinutes = (int) Cache::remember('setting_lockout_duration', 300, function () {
                return Setting::where('key', 'lockout_duration')->value('value') ?? 15;
            });

            if ($user) {
                $attempts = $user->failed_login_attempts + 1;

                if ($maxAttempts > 0 && $attempts >= $maxAttempts) {
                    // Lock the account
                    $lockedUntil = now()->addMinutes($lockoutMinutes);
                    $user->update([
                        'failed_login_attempts' => $attempts,
                        'locked_until'          => $lockedUntil,
                    ]);

                    ActivityLog::log(
                        'Account Locked',
                        "Account locked for {$user->email} after {$attempts} failed login attempts."
                    );

                    throw ValidationException::withMessages([
                        'email' => "Too many failed login attempts. Your account has been locked for "
                                 . "{$lockoutMinutes} minute(s).",
                    ]);
                } else {
                    // Increment attempts
                    $user->increment('failed_login_attempts');
                    $remaining = $maxAttempts > 0 ? ($maxAttempts - $attempts) : null;

                    $msg = __('auth.failed');
                    if ($maxAttempts > 0 && $remaining !== null && $remaining > 0) {
                        $msg .= " ({$remaining} attempt(s) remaining before lockout)";
                    }

                    throw ValidationException::withMessages(['email' => $msg]);
                }
            }

            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        $user = Auth::user();

        // ── Check if user is active ───────────────────────────────────────
        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('Your account has been deactivated. Please contact your system administrator.'),
            ]);
        }

        // ── Successful login — reset failed attempts ──────────────────────
        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until'          => null,
            'last_login_at'         => now(),
        ])->save();

        // Regenerate session FIRST — before writing any session data
        $request->session()->regenerate();

        // Load user's preferred language into session
        if ($user->language && array_key_exists($user->language, LanguageController::SUPPORTED)) {
            session(['locale' => $user->language]);
        }

        // Load user's preferred currency into session
        $userCurrency = Currency::where('code', strtoupper($user->currency ?? 'INR'))
            ->where('status', 'active')
            ->first();
        if (!$userCurrency) {
            $userCurrency = Currency::where('is_default', true)->where('status', 'active')->first();
        }
        if ($userCurrency) {
            session(['active_currency' => $userCurrency]);
        }

        // Store remember preference so middleware can rehydrate session if needed
        session(['remember_me' => $remember]);

        ActivityLog::log('Login', 'User authenticated and logged into the system.');

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            ActivityLog::log('Logout', 'User logged out of the system.');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out successfully.');
    }
}
