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
        $locale = $request->route('locale');
        $supported = config('app.available_locales', ['en']);

        if ($locale && in_array($locale, $supported)) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        URL::defaults(['locale' => App::getLocale() === 'en' ? null : App::getLocale()]);

        return $next($request);
    }
}
