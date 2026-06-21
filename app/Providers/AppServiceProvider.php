<?php

namespace App\Providers;

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
        $adminLangPath = app()->langPath() . '/admin';

        if (is_dir($adminLangPath)) {
            $translator = $this->app['translator'];

            foreach (glob($adminLangPath . '/*.php') as $file) {
                $locale = pathinfo($file, PATHINFO_FILENAME);
                $translations = include $file;

                if (is_array($translations)) {
                    $lines = [];

                    foreach ($translations as $key => $value) {
                        $lines["admin.$key"] = $value;
                    }

                    $translator->addLines($lines, $locale);
                    // Also write a compatibility copy at lang/{locale}/admin.php so
                    // packages or scripts expecting the standard Laravel layout
                    // continue to work. We only write when content differs to avoid
                    // touching files unnecessarily.
                    $localeDir = app()->langPath() . '/' . $locale;
                    if (!is_dir($localeDir)) {
                        @mkdir($localeDir, 0777, true);
                    }

                    $destFile = $localeDir . '/admin.php';
                    $export = var_export($translations, true);
                    $content = "<?php\n\nreturn " . $export . ";\n";

                    if (!file_exists($destFile) || file_get_contents($destFile) !== $content) {
                        @file_put_contents($destFile, $content);
                    }
                }
            }
        }
    }
}
