<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\City;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cities', function () {
    $cities = City::orderBy('name')->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

Route::get('/cities/{country}', function (string $country) {
    $cities = City::where('country', $country)->orderBy('name')->get(['id', 'name', 'name_ru', 'country', 'latitude', 'longitude', 'timezone_gmt']);
    return response()->json($cities);
});

Route::get('/cities/search/{query}', function (string $query) {
    $queryLower = mb_strtolower($query);
    $queryNormalized = str_replace(['-', ' '], '', $queryLower);

    $cities = City::search($query)
        ->orderByRaw("
            CASE
                WHEN LOWER(name) = ? OR LOWER(name_ru) = ? THEN 0
                WHEN LOWER(name) LIKE ? OR LOWER(name_ru) = ? THEN 1
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

Route::post('/calculate', [App\Http\Controllers\NatalChartController::class, 'processAsync'])->name('calculate');

// Token-based access (no auth required)
Route::get('/charts/access/{token}', [App\Http\Controllers\NatalChartController::class, 'accessViaToken'])
    ->name('charts.access');

Route::get('/charts/{natalChart}/set-password', [App\Http\Controllers\NatalChartController::class, 'showSetPassword'])
    ->name('charts.set-password');

Route::post('/charts/{natalChart}/set-password', [App\Http\Controllers\NatalChartController::class, 'storePassword'])
    ->name('charts.store-password');

// Protected routes
// Route::get('/processing'...) removed as it's now AJAX
Route::get('/charts/{natalChart}', [App\Http\Controllers\NatalChartController::class, 'show'])
    ->middleware('auth')
    ->name('charts.show');

Route::post('/charts/{natalChart}/generate-report', [App\Http\Controllers\NatalChartController::class, 'generateReport'])
    ->middleware('auth')
    ->name('charts.generate-report');

Route::get('/charts/{natalChart}/status', [App\Http\Controllers\NatalChartController::class, 'checkStatus'])
    ->middleware('auth')
    ->name('charts.status');

// Chat routes (auth only, verified not required after password set)
Route::get('/charts/{natalChart}/chat', [App\Http\Controllers\AstrologyChatController::class, 'index'])
    ->middleware('auth')
    ->name('charts.chat');

Route::post('/charts/{natalChart}/chat', [App\Http\Controllers\AstrologyChatController::class, 'send'])
    ->middleware('auth')
    ->name('charts.chat.send');

Route::delete('/charts/{natalChart}/chat', [App\Http\Controllers\AstrologyChatController::class, 'clear'])
    ->middleware('auth')
    ->name('charts.chat.clear');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Also apply auth to dashboard
    Route::get('/dashboard', [App\Http\Controllers\NatalChartController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
