<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Supported languages with their labels and flag emojis.
     */
    public const SUPPORTED = [
        'en' => ['label' => 'English',    'flag' => '🇬🇧'],
        'gu' => ['label' => 'ગુજરાતી',    'flag' => '🇮🇳'],
        'hi' => ['label' => 'हिन्दी',      'flag' => '🇮🇳'],
    ];

    /**
     * Switch application locale and store in session.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (array_key_exists($locale, self::SUPPORTED)) {
            session(['locale' => $locale]);
        }

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
