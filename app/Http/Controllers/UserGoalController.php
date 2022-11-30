<?php

namespace App\Http\Controllers;

use App\Enums\StravaSportTypeEnum;
use App\Http\Requests\StoreUserGoalRequest;
use App\Models\UserGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserGoalController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->user();
        $userGoal = UserGoal::whereUserId($user->id)->first();
        return Inertia::render('UserGoalOverview', [
            'userGoal' => $userGoal,
            'sportTypes' => StravaSportTypeEnum::supportedForGoals()
        ]);
    }

    public function store(StoreUserGoalRequest $req)
    {
        $user = $req->user();
        // @todo: Move to service
        $userGoal = UserGoal::create([
            'user_id' => $user->id,
            'name' => $req->name(),
            'start' => $req->start()->toDateString(),
            'end' => $req->end()->toDateString()
        ]);
        $userGoal->sportTypes()->sync($req->selectedSportTypes());

        return Redirect::route('dashboard.goals');
    }
}
