<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\City;

// Token-based access MUST be defined BEFORE locale group to avoid route conflicts
Route::get('/charts/access/{token}', [App\Http\Controllers\NatalChartController::class, 'accessViaToken'])
    ->middleware('set-locale')
    ->name('charts.access');
Route::get('/charts/preview/{token}', [App\Http\Controllers\NatalChartController::class, 'preview'])
    ->middleware('set-locale')
    ->name('charts.preview');
Route::get('/charts/{natalChart}/set-password', [App\Http\Controllers\NatalChartController::class, 'showSetPassword'])
    ->middleware('set-locale')
    ->name('charts.set-password');
Route::post('/charts/{natalChart}/set-password', [App\Http\Controllers\NatalChartController::class, 'storePassword'])
    ->middleware('set-locale')
    ->name('charts.store-password');

// Shared route definitions for both English (no prefix) and locale-prefixed routes
$registerRoutes = function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    // Magic login routes
    Route::get('/login', [App\Http\Controllers\Auth\MagicLinkController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login/send', [App\Http\Controllers\Auth\MagicLinkController::class, 'sendLoginLink'])
        ->name('magic.login.send');
    Route::get('/login/{token}', [App\Http\Controllers\Auth\MagicLinkController::class, 'loginViaToken'])
        ->name('magic.login.token');
    Route::post('/logout', [App\Http\Controllers\Auth\MagicLinkController::class, 'logout'])
        ->name('logout');

    // Protected routes
    Route::get('/charts/{natalChart}', [App\Http\Controllers\NatalChartController::class, 'show'])
        ->middleware('auth')
        ->name('charts.show');
    Route::post('/charts/{natalChart}/generate-report', [App\Http\Controllers\NatalChartController::class, 'generateReport'])
        ->middleware('auth')
        ->name('charts.generate-report');
    Route::get('/charts/{natalChart}/status', [App\Http\Controllers\NatalChartController::class, 'checkStatus'])
        ->middleware('auth')
        ->name('charts.status');

    // Chat routes (auth only)
    Route::get('/charts/{natalChart}/chat', [App\Http\Controllers\AstrologyChatController::class, 'index'])
        ->middleware('auth')
        ->name('charts.chat');
    Route::post('/charts/{natalChart}/chat', [App\Http\Controllers\AstrologyChatController::class, 'send'])
        ->middleware('auth')
        ->name('charts.chat.send');
    Route::get('/charts/{natalChart}/chat/{chatMessage}/status', [App\Http\Controllers\AstrologyChatController::class, 'status'])
        ->middleware('auth')
        ->name('charts.chat.status');
    Route::delete('/charts/{natalChart}/chat', [App\Http\Controllers\AstrologyChatController::class, 'clear'])
        ->middleware('auth')
        ->name('charts.chat.clear');

    // Blog
    Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

    // Horoscope
    Route::get('/horoscope', [App\Http\Controllers\HoroscopeController::class, 'index'])->name('horoscope.index');
    Route::get('/horoscope/{sign}', [App\Http\Controllers\HoroscopeController::class, 'show'])->name('horoscope.sign');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
};

// English routes (no prefix)
Route::middleware('set-locale')->group($registerRoutes);

// Locale-prefixed routes
$locales = array_filter(config('app.available_locales', ['en']), fn($l) => $l !== 'en');
foreach ($locales as $locale) {
    Route::middleware('set-locale')->prefix($locale)->name("{$locale}.")->group($registerRoutes);
}

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('users');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\DashboardController::class, 'userShow'])->name('users.show');
        Route::get('/charts', [App\Http\Controllers\Admin\DashboardController::class, 'charts'])->name('charts');
        Route::get('/messages', [App\Http\Controllers\Admin\DashboardController::class, 'messages'])->name('messages');
        Route::get('/charts/{natalChart}/conversation', [App\Http\Controllers\Admin\DashboardController::class, 'conversation'])->name('conversation');
        Route::get('/blog', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blog.index');
        Route::get('/blog/create', [App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blog.create');
        Route::post('/blog', [App\Http\Controllers\Admin\BlogController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}/edit', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('blog.destroy');
        Route::get('/blog/{post}/preview', [App\Http\Controllers\Admin\BlogController::class, 'preview'])->name('blog.preview');
    });

// Privacy & Terms (always English, no locale prefix)
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// SEO: sitemap and robots (no locale prefix)
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $sitemapUrl = rtrim(config('app.url', 'https://natalscope.com'), '/') . '/sitemap.xml';
    return response("User-agent: *\nAllow: /\n\nSitemap: {$sitemapUrl}\n", 200, ['Content-Type' => 'text/plain']);
})->name('robots');

// Calculate route (no locale prefix, AJAX endpoint)
Route::post('/calculate', [App\Http\Controllers\NatalChartController::class, 'processAsync'])
    ->middleware('set-locale')
    ->name('calculate');

// API-style routes (no locale prefix, always available)
Route::get('/cities', function () {
    $cities = City::orderBy('name')->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

Route::get('/cities/{country}', function (string $country) {
    $cities = City::where('country', $country)->orderBy('name')->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

Route::get('/cities/search/{query}', function (string $query) {
    $query = urldecode($query);
    $query = mb_convert_encoding($query, 'UTF-8', 'auto');

    $queryLower = mb_strtolower($query);
    $queryNormalized = str_replace(['-', ' '], '', $queryLower);

    $cities = City::search($query)
        ->orderByRaw("
            CASE
                WHEN LOWER(name) = ? OR LOWER(name_ru) = ? THEN 0
                WHEN LOWER(name) LIKE ? OR LOWER(name_ru) LIKE ? THEN 1
                WHEN LOWER(alternate_names) LIKE ? THEN 2
                WHEN LOWER(name) LIKE ? OR LOWER(name_ru) LIKE ? THEN 3
                WHEN LOWER(name_normalized) LIKE ? THEN 4
                ELSE 5
            END
        ", [
            $queryLower, $queryLower,
            $queryLower . '%', $queryLower,
            '%,' . $queryLower . ',%',
            $queryLower . '%', $queryLower . '%',
            $queryLower . '%'
        ])
        ->orderBy('name')
        ->limit(50)
        ->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});
