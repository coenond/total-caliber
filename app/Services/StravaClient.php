<?php

namespace App\Services;

use App\Models\StravaAccessToken;
use App\Models\StravaAuthToken;
use App\Models\StravaRefreshToken;
use App\Models\User;
use Carbon\Carbon;
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
        return Http::post($this->url('oauth/token'), [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $authToken->code,
            'grant_type' => 'authorization_code'
        ]);
    }

    public function refreshAccessToken(StravaRefreshToken $refreshToken): Response
    {
        return Http::post($this->url('oauth/token'), [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken->token,
            'grant_type' => 'refresh_token'
        ]);
    }

    public function getActivities(User $user, int $page, int $perPage, ?Carbon $from, ?Carbon $to): Response
    {
        $accessToken = $this->getValidAccessToken($user);

        $query = [
            'page' => $page,
            'per_page' => $perPage
        ];

        if ($from) $query['after'] = $from->timestamp;
        if ($to) $query['before'] = $to->timestamp;

        $result = Http::withToken($accessToken)->get($this->url('athlete/activities'), $query);
        if (!$result->successful()) {
            info($result);
            throw new Error('Request failed with code' . $result->status());
        }
        return $result;
    }

    private function getValidAccessToken(User $user): string
    {
        $accessToken = StravaAccessToken::whereUserId($user->id)->firstOrFail();

        if ($accessToken->isNotExpired()) {
            return $accessToken->token;
        }

        $refreshToken = StravaRefreshToken::whereUserId($user->id)->firstOrFail();
        $refreshTokenResponse = $this->refreshAccessToken($refreshToken);

        $refreshToken->token = $refreshTokenResponse->object()->refresh_token;
        $refreshToken->save();

        $accessToken->token = $refreshTokenResponse->object()->refresh_token;
        $accessToken->expires_at = $refreshTokenResponse->object()->expires_at;
        $accessToken->save();

        return $accessToken->token;
    }

    private function url(string $uri): string
    {
        return $this->url . $uri;
    }
}
