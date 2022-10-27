<?php

namespace App\Services;

class StravaAuthService
{
    private CONST baseUrl = 'https://www.strava.com';


    public function getAuthorizationUrl(): string
    {
        return self::baseUrl . '/oauth/authorize?' . http_build_query([
            'client_id' => env('STRAVA_CLIEND_ID'),
            'response_type' => 'code',
            'approval_prompt' => 'force',
            'scope' => 'activity:read,activity:write',
            'redirect_uri' => url('/strava/authorize')
        ]);
    }
}