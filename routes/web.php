<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\City;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/cities', function () {
    $cities = City::orderBy('name')->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

Route::get('/cities/{country}', function (string $country) {
    $cities = City::where('country', $country)->orderBy('name')->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

Route::get('/cities/search/{query}', function (string $query) {
    // Properly decode URL-encoded Cyrillic characters
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
        ->orderByRaw("CASE WHEN country = 'RU' THEN 0 WHEN country = 'UA' THEN 1 WHEN country = 'BY' THEN 2 ELSE 3 END")
        ->orderBy('name')
        ->limit(50)
        ->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

// Partner compatibility verification (public - no auth required)
Route::get('/compatibility/verify/{token}', [App\Http\Controllers\PartnerCompatibilityController::class, 'verify'])
    ->name('compatibility.verify');
Route::post('/compatibility/verify/{token}', [App\Http\Controllers\PartnerCompatibilityController::class, 'confirm'])
    ->name('compatibility.confirm');

// Public sharing of compatibility (keep for backwards compatibility)
Route::get('/compatibility/shared/{token}', [App\Http\Controllers\CompatibilityController::class, 'shared'])
    ->name('compatibility.shared');

Route::post('/calculate', [App\Http\Controllers\NatalChartController::class, 'processAsync'])->name('calculate');

// Token-based access (no auth required)
Route::get('/charts/access/{token}', [App\Http\Controllers\NatalChartController::class, 'accessViaToken'])
    ->name('charts.access');

Route::get('/charts/preview/{token}', [App\Http\Controllers\NatalChartController::class, 'preview'])
    ->name('charts.preview');

Route::get('/charts/{natalChart}/set-password', [App\Http\Controllers\NatalChartController::class, 'showSetPassword'])
    ->name('charts.set-password');

Route::post('/charts/{natalChart}/set-password', [App\Http\Controllers\NatalChartController::class, 'storePassword'])
    ->name('charts.store-password');

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

// Partner compatibility routes (auth required)
Route::get('/charts/{natalChart}/compatibility', [App\Http\Controllers\PartnerCompatibilityController::class, 'show'])
    ->middleware('auth')
    ->name('charts.compatibility.show');

Route::post('/charts/{natalChart}/compatibility', [App\Http\Controllers\PartnerCompatibilityController::class, 'store'])
    ->middleware('auth')
    ->name('charts.compatibility.store');

Route::get('/compatibility/{compatibility}/ai-status', [App\Http\Controllers\PartnerCompatibilityController::class, 'checkAiReportStatus'])
    ->middleware('auth')
    ->name('compatibility.ai-status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
