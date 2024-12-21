<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale'));

        // Log the retrieved locale
        Log::info("LocaleMiddleware: Retrieved locale from session: {$locale}");

        if (array_key_exists($locale, config('app.available_locales'))) {
            App::setLocale($locale);
            Log::info("LocaleMiddleware: Application locale set to: {$locale}");
        } else {
            Log::warning("LocaleMiddleware: Locale '{$locale}' is not supported. Falling back to default.");
        }

        return $next($request);
    }
}
