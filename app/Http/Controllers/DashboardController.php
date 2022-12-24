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

            'weekDataChartDataInTime' => $weekDataChart ? array_values($weekDataChart['data_in_time']) : null,
            'weekDataChartDataInDistance' => $weekDataChart ? array_values($weekDataChart['data_in_distance']) : null,
            'weekDataChartLabels' => $weekDataChart ? array_values($weekDataChart['labels']) : null,

            'yearOverviewDataChartDataInTime' => $yearDataChart ? array_values($yearDataChart['data_in_time']) : null,
            'yearOverviewDataChartDataInDistance' => $yearDataChart ? array_values($yearDataChart['data_in_distance']) : null,
            'yearOverviewDataChartLabels' => $yearDataChart ? array_values($yearDataChart['labels']) : null,

            'success_message' => session()->get('success_message')
        ]);
    }
}
