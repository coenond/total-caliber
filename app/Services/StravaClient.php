<?php

namespace App\Services;

use App\Models\StravaAuthToken;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class StravaClient
{
    private $url;
    private $clientId;
    private $clientSecret;

    public function __construct()
    {
        $this->url = env('STRAVA_URL');
        $this->clientId = env('STRAVA_CLIENT_ID');
        $this->clientSecret = env('STRAVA_CLIENT_SECRET');
    }

    public function getAccessToken(StravaAuthToken $authToken): Response
    {
        return Http::post($this->url('api/v3/oauth/token'), [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $authToken->code,
            'grant_type' => 'authorization_code'
        ]);
    }

    private function url(string $uri): string
    {
        return $this->url . $uri;
    }
}
