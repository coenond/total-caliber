<?php

namespace App\Http\Controllers;

use App\Enums\StravaSportTypeEnum;
use App\Http\Requests\StoreUserGoalRequest;
use App\Http\Requests\StoreUserStravaDescriptionRequest;
use App\Jobs\SyncStravaActivities;
use App\Models\StravaSyncJob;
use App\Models\UserGoal;
use App\Models\UserStravaDescription;
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

        // $stravaJobModel = StravaSyncJob::create(['user_id' => $user->id]);
        // SyncStravaActivities::dispatch($user, $stravaJobModel, 1, Carbon::now()->subYears(10), Carbon::now());

        return Redirect::route('onboarding.setGoal')->with('message', 'We started syncing your activities in the background. This can take up to 5 minutes.');
    }

    public function storeGoal(StoreUserGoalRequest $req)
    {
        $user = $req->user();
        // @todo: Move to service
        $userGoal = UserGoal::updateOrCreate([ 'user_id' => $user->id ], [
            'name' => $req->name(),
            'start' => $req->start()->toDateString(),
            'end' => $req->end()->toDateString()
        ]);
        $userGoal->sportTypes()->sync($req->selectedSportTypes());

        $message = $userGoal->create_at === $userGoal->updated_at
            ? 'Your goal is created.'
            : 'Goal is updated.';

        return Redirect::route('onboarding.setStravaDescription')->with('message', $message);
    }

    public function setStravaDescription(Request $req): Response
    {
        $user = $req->user();
        $userGoal = UserGoal::whereUserId($user->id)->with('sportTypes')->first();
        $stravaDescription = UserStravaDescription::whereUserId($user->id)->first();
        return Inertia::render('Onboarding/StravaDescription', [
            'sportTypeOptions' => StravaSportTypeEnum::supportedForGoals(),
            'userGoal' => $userGoal,
            'stravaDescription' => $stravaDescription,
            'startReadable' => $userGoal->start->toFormattedDateString(),
            'endReadable' => $userGoal->end->toFormattedDateString(),
        ]);
    }

    public function storeStravaDescription(StoreUserStravaDescriptionRequest $req)
    {
        $user = $req->user();
        // @todo: Move to service
        UserStravaDescription::updateOrCreate([ 'user_id' => $user->id ], [
            'enabled' => $req->enabled(),
            'totals' => $req->showTotals(),
            'week_stats' => $req->showWeekStats(),
            'month_stats' => $req->showMonthStats()
        ]);

        $message = $req->enabled()
            ? 'Strava description settings saved.'
            : 'Strava description is disabled.';

        return Redirect::route('onboarding.final')->with('message', $message);
    }

    public function finalPage(): Response
    {
        return Inertia::render('Onboarding/Final');
    }

}
