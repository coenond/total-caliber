<?php

namespace App\Http\Controllers;

use App\Services\StravaAuthService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class ProfileController extends Controller
{
    public function __construct(
        private StravaAuthService $stravaAuthService
    ) { }

    public function renderPage(Request $req): Response
    {
        $user = $req->user();

        return Inertia::render('Profile', [
            'stravaAuthUrl' => $this->stravaAuthService->getAuthorizationUrl(false)
        ]);
    }
}
