<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class ProfileController extends Controller
{

    public function renderPage(Request $req): Response
    {
        return Inertia::render('Profile');
    }
}
