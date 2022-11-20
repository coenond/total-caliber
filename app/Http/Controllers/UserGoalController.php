<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserGoalRequest;
use App\Models\UserGoal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserGoalController extends Controller
{
    public function index()
    {
        return Inertia::render('UserGoalOverview');
    }

    public function store(StoreUserGoalRequest $req)
    {
        $user = $req->user();
        // @todo: Move to service
        UserGoal::create([
            'user_id' => $user->id,
            'name' => $req->name(),
            'start' => $req->start(),
            'end' => $req->end()
        ]);
        return Inertia::render('UserGoalOverview');
    }
}
