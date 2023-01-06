<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserGoalController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Auth\StravaAuthorizeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', function () {
    $faq1 = [
        [
            'q' => 'When will the Strava description of my activity be updated?',
            'a' => 'We only add the Total Caliber report to newly created activities. This means that it will not update the description of activities in the past.'
        ],
        [
            'q' => 'My totals calculation is wrong.',
            'a' => 'It could be that not all of your activities are synced between Strava and Total Caliber. Hit the "Sync my activities" button on the "My Activities page" to solve this. It could also be that you didn\'t give Total Caliber permission to access your Strava private activities. We cannot calculate that if that is the case.'
        ],
        [
            'q' => 'Can I set multiple goals?',
            'a' => 'Currently it\'s only possible to test one goal.'
        ],
        [
            'q' => 'The Total Caliber report is not showing in my new activities.',
            'a' => 'Reports only get added to your Strava Activities when you\'ve set a goal, and if the day of you activity falls inside the start and end date of your goal.'
        ]
    ];
    $faq2 = [
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
        'faq2' => $faq2,
    ]);
});

Route::get('health', HealthCheckResultsController::class);

Route::prefix('onboarding')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [OnboardingController::class, 'index'])->name('authorizedFromOnboarding');
    Route::get('/strava/authorize', [StravaAuthorizeController::class, 'authStrava']);
    Route::get('/set-goal', [OnboardingController::class, 'setGoal'])->name('onboarding.setGoal');
    Route::post('/set-goal', [OnboardingController::class, 'storeGoal'])->name('onboarding.setGoal');
    Route::post('/syncActivities', [OnboardingController::class, 'createSyncJob']);
    Route::get('/strava-description', [OnboardingController::class, 'setStravaDescription'])->name('onboarding.setStravaDescription');
    Route::post('/strava-description', [OnboardingController::class, 'storeStravaDescription'])->name('onboarding.storeStravaDescription');
    Route::get('/final', [OnboardingController::class, 'finalPage'])->name('onboarding.final');
});

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'renderDashboardPage'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'renderPage'])->name('dashboard.profile');
    Route::get('/goals', [UserGoalController::class, 'index'])->name('dashboard.goals');
    Route::post('/goals', [UserGoalController::class, 'store'])->name('dashboard.goals.store');
    Route::post('/goals/strava-description', [UserGoalController::class, 'storeStravaDescription'])->name('dashboard.goals.storeStravaDescription');
    Route::get('/my-activities', [ActivityController::class, 'renderPage'])->name('my-activities');
    Route::post('/my-activities/create-sync-job', [ActivityController::class, 'createSyncJob']);
});

require __DIR__.'/auth.php';
