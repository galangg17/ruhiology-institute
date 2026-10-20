<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set active application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['id', 'en'];

        // 1. Explicit query parameter switch (e.g. ?lang=en)
        if ($request->has('lang') && in_array($request->query('lang'), $supportedLocales, true)) {
            $locale = $request->query('lang');
            session(['locale' => $locale]);
            cookie()->queue('locale', $locale, 60 * 24 * 365);
        }
        // 2. Session preference
        elseif (session()->has('locale') && in_array(session('locale'), $supportedLocales, true)) {
            $locale = session('locale');
        }
        // 3. Cookie preference
        elseif ($request->hasCookie('locale') && in_array($request->cookie('locale'), $supportedLocales, true)) {
            $locale = $request->cookie('locale');
            session(['locale' => $locale]);
        }
        // 4. Auto-detect from browser Accept-Language
        else {
            $preferred = $request->getPreferredLanguage($supportedLocales);
            $browserLang = strtolower((string) $request->header('Accept-Language', ''));
            
            // If browser specifically asks for Indonesian
            if (str_starts_with($browserLang, 'id') || $preferred === 'id') {
                $locale = 'id';
            } else {
                // Global / International default
                $locale = $preferred ?: 'id';
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
