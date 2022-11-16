<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserGoalController extends Controller
{
    public function index()
    {
        return Inertia::render('UserGoalOverview');
    }
}
