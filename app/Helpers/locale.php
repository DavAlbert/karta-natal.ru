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
        if ($locale !== 'en') {
            $parameters['locale'] = $locale;
        }
        return route($name, $parameters, $absolute);
    }
}
