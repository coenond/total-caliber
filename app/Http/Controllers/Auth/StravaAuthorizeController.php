<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StravaAuthToken;
use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StravaAuthorizeController extends Controller
{
    public function __construct(
        private StravaAuthService $stravaAuthService
    ) { }

    public function authorized(Request $req): Response
    {
        $user = $req->user();
        /** @var StravaAuthToken */
        $stravaAuthToken = $this->stravaAuthService->storeUserStravaCode(
            $user, $req->get('code'), $req->get('scope')
        );

        $this->stravaAuthService->setUserInitialRefreshToken($stravaAuthToken);
 
        return Inertia::render('Dashboard', ['success_message' => 'Strava succesfully authenticated']);
    }
}
