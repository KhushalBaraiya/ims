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
        // Set locale from session on every request
        if (session()->has('locale')) {
            $locale = session('locale');
            if (array_key_exists($locale, LanguageController::SUPPORTED)) {
                app()->setLocale($locale);
            }
        }

        // Share supported languages with all views
        View::share('supportedLanguages', LanguageController::SUPPORTED);
    }
}
