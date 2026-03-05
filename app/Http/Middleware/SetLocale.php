<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.available_locales', ['en']);
        $locale = null;

        // Check the first URL segment for a locale prefix
        $firstSegment = $request->segment(1);
        $hasLocalePrefix = $firstSegment && in_array($firstSegment, $supported) && $firstSegment !== 'en';

        if ($hasLocalePrefix) {
            // Explicit non-English locale in URL
            $locale = $firstSegment;
        } elseif ($this->isLocaleAwareRoute($request, $supported)) {
            // URL has no locale prefix but is a locale-aware route → English
            $locale = 'en';
        } elseif (Auth::check() && Auth::user()->locale && in_array(Auth::user()->locale, $supported)) {
            $locale = Auth::user()->locale;
        } elseif ($request->session()->has('locale') && in_array($request->session()->get('locale'), $supported)) {
            $locale = $request->session()->get('locale');
        } else {
            $locale = $this->detectBrowserLocale($request, $supported);
        }

        // Remember locale in session for subsequent requests
        $request->session()->put('locale', $locale);

        App::setLocale($locale);

        return $next($request);
    }

    /**
     * Check if the current route is one that has locale-prefixed variants.
     * API routes like /cities, /calculate etc. are NOT locale-aware.
     */
    private function isLocaleAwareRoute(Request $request, array $supported): bool
    {
        $path = trim($request->path(), '/');
        $segment = explode('/', $path)[0] ?? '';

        // These top-level paths have locale-prefixed variants
        $localeAwarePrefixes = ['', 'login', 'logout', 'charts', 'horoscope', 'profile'];

        return in_array($segment, $localeAwarePrefixes);
    }

    private function detectBrowserLocale(Request $request, array $supported): string
    {
        $acceptLanguage = $request->header('Accept-Language', '');

        // Parse Accept-Language header and sort by quality
        preg_match_all('/([a-zA-Z]{1,8}(?:-[a-zA-Z]{1,8})*)\s*(?:;\s*q\s*=\s*([\d.]+))?/', $acceptLanguage, $matches);

        if (empty($matches[1])) {
            return config('app.locale', 'en');
        }

        $languages = [];
        foreach ($matches[1] as $i => $lang) {
            $quality = isset($matches[2][$i]) && $matches[2][$i] !== '' ? (float) $matches[2][$i] : 1.0;
            $languages[] = ['lang' => strtolower($lang), 'q' => $quality];
        }

        usort($languages, fn($a, $b) => $b['q'] <=> $a['q']);

        foreach ($languages as $entry) {
            $lang = $entry['lang'];
            // Exact match (e.g., "fr")
            if (in_array($lang, $supported)) {
                return $lang;
            }
            // Prefix match (e.g., "fr-FR" → "fr")
            $prefix = explode('-', $lang)[0];
            if (in_array($prefix, $supported)) {
                return $prefix;
            }
        }

        return config('app.locale', 'en');
    }
}
