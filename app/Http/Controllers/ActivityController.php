<?php

namespace App\Http\Controllers;

use App\Jobs\SyncStravaActivities;
use App\Models\StravaSyncJob;
use App\Services\StravaActivityService;
use App\Utils\QuerySorter;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ActivityController extends Controller
{
    public function __construct(
        private StravaActivityService $stravaActivityService
    ) { }

    public function renderPage(Request $req): Response
    {
        $user = $req->user();
        $sorter = new QuerySorter('start_date', true);
        $activities = $this->stravaActivityService->getListFromDb($user, 100, $sorter)->values();

        return Inertia::render('MyActivities', [
            'activities' => $activities,
            'syncIsOnCoolDown' => $user->hasSyncJobOnCoolDown()
        ]);
    }

    public function createSyncJob(Request $req): RedirectResponse
    {
        $user = $req->user();
        $stravaJobModel = StravaSyncJob::create(['user_id' => $user->id]);

        SyncStravaActivities::dispatch($user, $stravaJobModel, 1, Carbon::now()->subYears(10), Carbon::now());
        return Redirect::route('my-activities');
    }
}
