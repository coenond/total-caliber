<?php

namespace App\Http\Controllers;

use App\Services\StravaActivityService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class ActivityController extends Controller
{
    public function __construct(
        private StravaActivityService $stravaActivityService
    ) { }

    public function renderPage(Request $req): Response
    {
        return Inertia::render('MyActivities', [
            'activities' => $this->stravaActivityService->getFromStrava($req->user())
        ]);
    }
}
