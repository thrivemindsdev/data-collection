<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $supported = ['en', 'hi'];
        if (!in_array($locale, $supported)) {
            $locale = config('app.locale', 'en');
        }

        // Set session
        session(['locale' => $locale]);
        session()->save();

        // Queue persistent cookie for 1 year
        cookie()->queue(cookie()->forever('app_locale', $locale));

        // Determine destination redirect
        $target = $request->query('redirect');
        if (!empty($target) && filter_var($target, FILTER_VALIDATE_URL)) {
            $parsedTarget = parse_url($target, PHP_URL_HOST);
            $currentHost = $request->getHost();
            if ($parsedTarget === $currentHost || empty($parsedTarget)) {
                return redirect($target)->withCookie(cookie()->forever('app_locale', $locale));
            }
        }

        $fallback = url()->previous();
        if (empty($fallback) || $fallback === url()->current() || str_contains($fallback, '/language/')) {
            $fallback = url('/');
        }

        return redirect($fallback)->withCookie(cookie()->forever('app_locale', $locale));
    }
}
