<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only check authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        // Skip timeout check if user logged in via "Remember Me" cookie
        // (viaRemember() returns true when authenticated through remember token)
        if (Auth::viaRemember()) {
            // Still update last_activity so manual timeout works after they interact
            session(['last_activity_time' => now()->timestamp]);
            return $next($request);
        }

        // Cache the timeout setting for 5 minutes — avoids DB hit on every request
        $timeoutMinutes = (int) Cache::remember('setting_session_timeout', 300, function () {
            return Setting::where('key', 'session_timeout')->value('value') ?? 0;
        });

        // 0 = disabled
        if ($timeoutMinutes <= 0) {
            return $next($request);
        }

        $lastActivity = session('last_activity_time');
        $now          = now()->timestamp;

        if ($lastActivity && ($now - $lastActivity) > ($timeoutMinutes * 60)) {
            // Timeout exceeded — log out and redirect
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message'  => 'Session expired. Please log in again.',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Your session expired due to inactivity. Please log in again.');
        }

        // Refresh last activity timestamp
        session(['last_activity_time' => $now]);

        return $next($request);
    }
}
