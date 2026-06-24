<?php

namespace App\Providers;

use App\Http\Controllers\LanguageController;
use App\Models\Currency;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Use Bootstrap 5 pagination views globally
        Paginator::useBootstrapFive();

        // Share supported languages with all views.
        View::share('supportedLanguages', LanguageController::SUPPORTED);

        // Share active currencies with every view for the navbar switcher.
        View::composer('*', function ($view) {
            static $activeCurrencies = null;
            if ($activeCurrencies === null) {
                try {
                    $activeCurrencies = Currency::where('status', 'active')
                        ->orderBy('name')
                        ->get();
                } catch (\Exception $e) {
                    $activeCurrencies = collect();
                }
            }
            $view->with('activeCurrencies', $activeCurrencies);
        });
    }
}
