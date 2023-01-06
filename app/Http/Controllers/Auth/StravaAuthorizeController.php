<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StravaAuthToken;
use App\Services\StravaAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StravaAuthorizeController extends Controller
{
    public function __construct(
        private StravaAuthService $stravaAuthService
    ) { }

    public function authorized(Request $req): RedirectResponse
    {
        $user = $req->user();
        /** @var StravaAuthToken */
        $stravaAuthToken = $this->stravaAuthService->storeUserStravaCode(
            $user, $req->get('code'), $req->get('scope')
        );

        $this->stravaAuthService->setUserInitialData($stravaAuthToken);
 
        return Redirect::route('dashboard')->with(['message' => 'Strava successfully authorized']);
    }

    public function authorizedFromOnboarding(Request $req): RedirectResponse
    {
        $user = $req->user();
        /** @var StravaAuthToken */
        $stravaAuthToken = $this->stravaAuthService->storeUserStravaCode(
            $user, $req->get('code'), $req->get('scope')
        );

        $this->stravaAuthService->setUserInitialData($stravaAuthToken);
 
        return Redirect::route('onboarding.setGoal')->with(['message' => 'Strava successfully authorized']);
    }
}
