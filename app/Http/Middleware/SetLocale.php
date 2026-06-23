<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LanguageController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Set the application locale from the session on every request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale');

        if ($locale && array_key_exists($locale, LanguageController::SUPPORTED)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
