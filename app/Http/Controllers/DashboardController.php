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
        return Inertia::render('Dashboard', [
            'stravaAuthUrl' => $this->stravaAuthService->getAuthorizationUrl(),
            'success_message' => $req->has('success_message') ? $req->input('success_message') : null
        ]);
    }
}
