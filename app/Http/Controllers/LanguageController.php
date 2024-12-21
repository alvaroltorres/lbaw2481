<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang($locale)
    {
        // Log the incoming locale
        Log::info("Attempting to switch locale to: {$locale}");

        // Validate the locale
        if (array_key_exists($locale, config('app.locales'))) {
            Session::put('locale', $locale);
            Log::info("Locale set to: {$locale}");
        } else {
            Log::warning("Locale '{$locale}' is not supported.");
        }

        return redirect()->back();
    }
}
