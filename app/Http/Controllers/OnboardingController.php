<?php

namespace App\Http\Controllers;

use App\Enums\StravaSportTypeEnum;
use App\Jobs\SyncStravaActivities;
use App\Models\StravaSyncJob;
use App\Models\UserGoal;
use App\Services\StravaAuthService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    /** */
    public function __construct(
        private StravaAuthService $stravaAuthService
    ) { }

    public function index(Request $req): Response
    {
        $user = $req->user();
        $userHasStravaAuth = $this->stravaAuthService->userHasStravaAuth($user);
        return Inertia::render('Onboarding/Onboarding', [
            'userHasStravaAuth' => $userHasStravaAuth,
            'stravaAuthUrl' => $this->stravaAuthService->getAuthorizationUrl(true),
            'syncIsOnCoolDown' => $user->hasSyncJobOnCoolDown(),
        ]);
    }

    public function setGoal(Request $req): Response
    {
        $user = $req->user();
        $userGoal = UserGoal::whereUserId($user->id)->with('sportTypes')->first();
        return Inertia::render('Onboarding/SetGoal', [
            'sportTypeOptions' => StravaSportTypeEnum::supportedForGoals(),
            'userGoal' => $userGoal
        ]);
    }


    public function createSyncJob(Request $req): RedirectResponse
    {
        $user = $req->user();
        $stravaJobModel = StravaSyncJob::create(['user_id' => $user->id]);

        // SyncStravaActivities::dispatch($user, $stravaJobModel, 1, Carbon::now()->subYears(10), Carbon::now());

        return Redirect::route('onboarding.setGoal')->with('message', 'We started syncing your activities in the background. This can take up to 5 minutes.');
    }
}
