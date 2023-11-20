<?php

namespace App\Http\Controllers;

use App\Enums\StravaSportTypeEnum;
use App\Http\Requests\StoreUserGoalRequest;
use App\Http\Requests\StoreUserStravaDescriptionRequest;
use App\Models\UserGoal;
use App\Models\UserStravaDescription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserGoalController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->user();
        $userGoal = UserGoal::whereUserId($user->id)
            ->whereDate('end', '>', Carbon::now()->toDateString())
            ->with('sportTypes')
            ->first();
        $userStravaDescription = UserStravaDescription::whereUserId($user->id)->first();

        return Inertia::render('UserGoalOverview', [
            'hasGoal' => !empty($userGoal),
            'name' => $userGoal ?  $userGoal->name : null,
            'start' => $userGoal ?  $userGoal->start->toDateString() : null,
            'startReadable' => $userGoal ?  $userGoal->start->toFormattedDateString() : null,
            'end' => $userGoal ?  $userGoal->end->toDateString() : null,
            'endReadable' => $userGoal ?  $userGoal->end->toFormattedDateString() : null,
            'sportTypes' => $userGoal ?  $userGoal->sportTypes->pluck('type') : null,

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

        $message = $userGoal->create_at === $userGoal->updated_at
            ? 'Your goal is created.'
            : 'Goal is updated.';

        return Redirect::route('dashboard.goals')->with('message', $message);
    }

    public function storeStravaDescription(StoreUserStravaDescriptionRequest $req)
    {
        $user = $req->user();
        // @todo: Move to service
        UserStravaDescription::updateOrCreate([ 'user_id' => $user->id ], [
            'enabled' => $req->enabled(),
            'simple' => $req->simple(),
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
