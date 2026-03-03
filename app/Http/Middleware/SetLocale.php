<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.available_locales', ['en']);

        // Try to get locale from route parameter first
        $locale = $request->route('locale');

        // If not found, check the first segment of the path
        if (!$locale) {
            $firstSegment = $request->segment(1);
            if ($firstSegment && in_array($firstSegment, $supported) && $firstSegment !== 'en') {
                $locale = $firstSegment;
            }
        }

        if ($locale && in_array($locale, $supported)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        URL::defaults(['locale' => App::getLocale() === 'en' ? null : App::getLocale()]);

        return $next($request);
    }
}
