<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserGoalController;
use App\Http\Controllers\ActivityController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', function () {
    $faq1 = [
        [
            'q' => 'What activity data does Total Caliber store from me?',
            'a' => 'We store the following fields: title, distance, moving time, elevation gain, start_date, timezone, calories burned, indoor trainer (y/n), commute (y/n), manual (y/n).'
        ],
        [
            'q' => 'How can I delete my account?',
            'a' => 'You can disconnect your account from your app settings on the Strava website, or under your profile within our dashboard.'
        ],
        [
            'q' => 'Is Total Caliber part of Strava?',
            'a' => 'No. totalcaliber.com is not part, or owned in any form by Strava. We use the Strava Open API to communicate between the systems.'
        ]
    ];
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'faq1' => $faq1,
        'faq2' => $faq1,
    ]);
});

Route::get('health', HealthCheckResultsController::class);

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'renderDashboardPage'])->name('dashboard');
    Route::get('/goals', [UserGoalController::class, 'index'])->name('dashboard.goals');
    Route::post('/goals', [UserGoalController::class, 'store'])->name('dashboard.goals.store');
    Route::post('/goals/strava-description', [UserGoalController::class, 'storeStravaDescription'])->name('dashboard.goals.storeStravaDescription');
    Route::get('/my-activities', [ActivityController::class, 'renderPage'])->name('my-activities');
    Route::post('/my-activities/create-sync-job', [ActivityController::class, 'createSyncJob']);
});

require __DIR__.'/auth.php';
