<?php

namespace App\Http\Controllers;

use App\Enums\StravaSportTypeEnum;
use App\Http\Requests\StoreUserGoalRequest;
use App\Http\Requests\StoreUserStravaDescriptionRequest;
use App\Models\UserGoal;
use App\Models\UserStravaDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserGoalController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->user();
        $userGoal = UserGoal::whereUserId($user->id)->first();
        $userStravaDescription = UserStravaDescription::whereUserId($user->id)->first();
        return Inertia::render('UserGoalOverview', [
            'hasGoal' => !empty($userGoal),
            'name' => $userGoal->name,
            'start' => $userGoal->start->toDateString(),
            'startReadable' => $userGoal->start->toFormattedDateString(),
            'end' => $userGoal->end->toDateString(),
            'endReadable' => $userGoal->end->toFormattedDateString(),

            'userStravaDescription' => $userStravaDescription,

            'sportTypeOptions' => StravaSportTypeEnum::supportedForGoals()
        ]);
    }

    public function store(StoreUserGoalRequest $req)
    {
        $user = $req->user();
        // @todo: Move to service
        $userGoal = UserGoal::updateOrCreate([ 'user_id' => $user->id ], [
            'name' => $req->name(),
            'start' => $req->start()->toDateString(),
            'end' => $req->end()->toDateString()
        ]);
        $userGoal->sportTypes()->sync($req->selectedSportTypes());

        return Redirect::route('dashboard.goals');
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

        return Redirect::route('dashboard.goals')->with('message', $message);
    }
}
