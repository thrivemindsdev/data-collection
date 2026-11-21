<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = ['en', 'hi'];
        $locale = null;

        // 1. Direct query parameter ?lang=en or ?lang=hi
        if ($request->has('lang') && in_array($request->query('lang'), $supported)) {
            $locale = $request->query('lang');
            session(['locale' => $locale]);
            session()->save();
            cookie()->queue(cookie()->forever('app_locale', $locale));
        }

        // 2. Check session
        if (!$locale && session()->has('locale') && in_array(session('locale'), $supported)) {
            $locale = session('locale');
        }

        // 3. Check cookie
        if (!$locale && $request->hasCookie('app_locale') && in_array($request->cookie('app_locale'), $supported)) {
            $locale = $request->cookie('app_locale');
            session(['locale' => $locale]);
        }

        // 4. Default fallback
        if (!$locale || !in_array($locale, $supported)) {
            $locale = config('app.locale', 'en');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
