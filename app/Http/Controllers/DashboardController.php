<?php

namespace App\Http\Controllers;

use App\Services\DataQueryService;
use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class DashboardController extends Controller
{
    /** */
    public function __construct(
        private StravaAuthService $stravaAuthService,
        private DataQueryService $dataQueryService,
    ) { }

    public function renderDashboardPage(Request $req): Response
    {
        $user = $req->user();
        $userHasStravaAuth = $this->stravaAuthService->userHasStravaAuth($user);

        $weekDataChart = $userHasStravaAuth
            ? $this->dataQueryService->getYearOverViewByWeek($user)
            : null;
        $yearDataChart = $userHasStravaAuth
            ? $this->dataQueryService->getYearProgress($user)
            : null;

        return Inertia::render('Dashboard', [
            'stravaAuthUrl' => $this->stravaAuthService->getAuthorizationUrl(),
            'userHasStravaAuth' => $userHasStravaAuth,
            'weekDataChartDataInTime' => array_values($weekDataChart['data_in_time']),
            'weekDataChartDataInDistance' => array_values($weekDataChart['data_in_distance']),
            'weekDataChartLabels' => array_values($weekDataChart['labels']),
            'success_message' => session()->get('success_message')
        ]);
    }
}
