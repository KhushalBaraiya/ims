<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LanguageController;
use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set the application locale and active currency on every request.
     *
     * Priority for locale:
     *   1. Session (user manually switched in this session)
     *   2. Logged-in user's saved language preference (user.language column)
     *   3. App default locale
     *
     * Priority for currency:
     *   1. Session (user manually switched in this session)
     *   2. Logged-in user's saved currency preference (user.currency column)
     *   3. System default currency (is_default = true)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ── 1. Resolve locale ──────────────────────────────────────────────
        $locale = session('locale');

        // If no session locale, fall back to the user's saved preference
        if (!$locale && Auth::check()) {
            $userLang = Auth::user()->language;
            if ($userLang && array_key_exists($userLang, LanguageController::SUPPORTED)) {
                $locale = $userLang;
                session(['locale' => $locale]); // cache it for this session
            }
        }

        if ($locale && array_key_exists($locale, LanguageController::SUPPORTED)) {
            app()->setLocale($locale);
        }

        // ── 2. Resolve active currency ─────────────────────────────────────
        // Only refresh if the session currency is missing/stale
        if (!session('active_currency') instanceof Currency && Auth::check()) {
            $userCurrencyCode = Auth::user()->currency;
            if ($userCurrencyCode) {
                $currency = Currency::where('code', strtoupper($userCurrencyCode))
                    ->where('status', 'active')
                    ->first();

                if ($currency) {
                    session(['active_currency' => $currency]);
                }
            }
        }

        return $next($request);
    }
}
