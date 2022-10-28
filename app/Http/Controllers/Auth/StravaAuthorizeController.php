<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StravaAuthToken;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StravaAuthorizeController extends Controller
{
    public function authorized(Request $request): Response
    {
        $user = $request->user();
        
        StravaAuthToken::updateOrCreate([
            'user_id' => $user->id,
            'code' => $request->get('code'),
            'scopes' => $request->get('scope')
        ]);
 
        return Inertia::render('Dashboard', ['success_message' => 'Strava succesfully authenticated']);
    }
}
