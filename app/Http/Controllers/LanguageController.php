<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Supported languages — no external flag images, using CSS color badges instead.
     * 'color'  = badge background colour
     * 'abbr'   = 2-letter country abbreviation shown in the badge
     */
    public const SUPPORTED = [
        'en' => ['label' => 'English',  'abbr' => 'GB', 'code' => 'EN'],
        'hi' => ['label' => 'Hindi',    'abbr' => 'IN', 'code' => 'HI'],
        'gu' => ['label' => 'Gujarati', 'abbr' => 'IN', 'code' => 'GU'],
    ];

    /**
     * Switch application locale and store in session.
     * Also persists to the user record so it survives re-login.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (array_key_exists($locale, self::SUPPORTED)) {
            session(['locale' => $locale]);

            // Persist to user record
            if (Auth::check()) {
                Auth::user()->update(['language' => $locale]);
            }
        }

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
