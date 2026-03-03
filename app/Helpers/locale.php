<?php

if (!function_exists('locale_url')) {
    /**
     * Generate a URL for the given path with the current locale prefix.
     */
    function locale_url(string $path = '/'): string
    {
        $locale = app()->getLocale();
        $prefix = ($locale === 'en') ? '' : '/' . $locale;
        return $prefix . $path;
    }
}

if (!function_exists('locale_route')) {
    /**
     * Generate a named route URL with the current locale.
     */
    function locale_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $locale = app()->getLocale();

        // Check if locale-specific route exists (e.g., horoscope.index.ru)
        $localeName = $name . '.' . $locale;
        if (\Illuminate\Support\Facades\Route::has($localeName)) {
            return route($localeName, $parameters, $absolute);
        }

        // Fall back to original route with locale parameter
        if ($locale !== 'en') {
            $parameters['locale'] = $locale;
        }
        return route($name, $parameters, $absolute);
    }
}

if (!function_exists('horoscope_url_for_locale')) {
    /**
     * Generate horoscope URL for a specific locale (for hreflang).
     */
    function horoscope_url_for_locale(?string $sign = null, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $baseUrl = config('app.url', 'https://natalscope.com');
        $path = '/horoscope';
        if ($sign) {
            $path .= '/' . $sign;
        }
        $prefix = ($locale === 'en') ? '' : '/' . $locale;
        return rtrim($baseUrl, '/') . $prefix . $path;
    }
}
