<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class DashboardController extends Controller
{
    /** */
    public function __construct(
        private StravaAuthService $stravaAuthService
    ) { }

    public function renderDashboardPage(Request $req): Response
    {
        $userHasStravaAuth = $this->stravaAuthService->userHasStravaAuth($req->user());

        return Inertia::render('Dashboard', [
            'stravaAuthUrl' => $this->stravaAuthService->getAuthorizationUrl(),
            'userHasStravaAuth' => $userHasStravaAuth,
            'success_message' => session()->get('success_message')
        ]);
    }
}
