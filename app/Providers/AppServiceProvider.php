<?php

namespace App\Providers;

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share supported languages with all views.
        // Locale is applied per-request by App\Http\Middleware\SetLocale
        // (after session middleware has run), not here.
        View::share('supportedLanguages', LanguageController::SUPPORTED);
    }
}
