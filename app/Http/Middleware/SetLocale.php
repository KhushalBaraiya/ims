<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Reads the locale stored in the session and applies it to the app.
     * Supported locales: en, hi, gu
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = ['en', 'hi', 'gu'];
        $locale    = session('locale', config('app.locale', 'en'));

        if (in_array($locale, $supported)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
