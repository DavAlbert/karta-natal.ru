<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $cities = \App\Models\City::orderBy('name')->get(['id', 'name', 'latitude', 'longitude', 'timezone_gmt']);
    $defaultCity = \App\Models\City::where('name', 'Москва')->first();
    return view('welcome', compact('cities', 'defaultCity'));
});

Route::get('/dashboard', [App\Http\Controllers\NatalChartController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    ->middleware(['auth', 'verified'])
    ->name('charts.show');

Route::post('/charts/{natalChart}/generate-report', [App\Http\Controllers\NatalChartController::class, 'generateReport'])
    ->middleware(['auth', 'verified'])
    ->name('charts.generate-report');

Route::get('/charts/{natalChart}/status', [App\Http\Controllers\NatalChartController::class, 'checkStatus'])
    ->middleware(['auth', 'verified'])
    ->name('charts.status');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
