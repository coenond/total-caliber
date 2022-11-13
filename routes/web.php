<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('health', HealthCheckResultsController::class);

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'renderDashboardPage'])->name('dashboard');
    Route::get('/my-activities', [ActivityController::class, 'renderPage'])->name('my-activities');
    Route::post('/my-activities/create-sync-job', [ActivityController::class, 'createSyncJob']);
});

require __DIR__.'/auth.php';
