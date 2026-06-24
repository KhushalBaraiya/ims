<?php

namespace App\Providers;

use App\Http\Controllers\LanguageController;
use App\Models\Currency;
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

        // Share all active currencies with every view so the navbar currency
        // switcher dropdown is always populated without per-controller queries.
        View::composer('*', function ($view) {
            // Only query the DB once per request cycle using a static variable.
            static $activeCurrencies = null;
            if ($activeCurrencies === null) {
                try {
                    $activeCurrencies = Currency::where('status', 'active')
                        ->orderBy('name')
                        ->get();
                } catch (\Exception $e) {
                    // During migrations or fresh installs the table may not exist yet.
                    $activeCurrencies = collect();
                }
            }
            $view->with('activeCurrencies', $activeCurrencies);
        });
    }
}
