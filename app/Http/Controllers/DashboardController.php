<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Inertia\Inertia;
use Inertia\Response;


class DashboardController extends Controller
{
    /** */
    public function __construct(
        private StravaAuthService $stravaAuthService
    ) { }

    public function renderDashboardPage(): Response
    {
        return Inertia::render('Dashboard', ['stravaAuthUrl' => $this->stravaAuthService->getAuthorizationUrl()]);
    }
}
