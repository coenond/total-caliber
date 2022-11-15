<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserGoalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'renderDashboardPage'])->name('dashboard');
    Route::get('/goals', [UserGoalController::class, 'index'])->name('dashboard.goals');
});

require __DIR__.'/auth.php';
