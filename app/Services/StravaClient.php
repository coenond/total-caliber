<?php

namespace App\Services;

use App\Models\StravaAccessToken;
use App\Models\StravaAuthToken;
use App\Models\StravaRefreshToken;
use App\Models\User;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class StravaClient
{
    /**
     * The grand type authorization_code should only be used when retrieving the first access token for the user.
     *
     * @param StravaAuthToken $authToken
     *
     * @return Response
     */
    public function requestAccessTokenByAuthToken(StravaAuthToken $authToken): Response
    {
        return Http::post($this->url('oauth/token'), [
            'client_id' => env('STRAVA_CLIENT_ID'),
            'client_secret' => env('STRAVA_CLIENT_SECRET'),
            'code' => $authToken->code,
            'grant_type' => 'authorization_code'
        ]);
    }

    /**
     * Request a new short live access token for the user.
     *
     * @param StravaRefreshToken $refreshToken
     *
     * @return Response
     */
    public function requestAccessTokenByRefreshToken(StravaRefreshToken $refreshToken): Response
    {
        return Http::post($this->url('oauth/token'), [
            'client_id' => env('STRAVA_CLIENT_ID'),
            'client_secret' => env('STRAVA_CLIENT_SECRET'),
            'refresh_token' => $refreshToken->token,
            'grant_type' => 'refresh_token'
        ]);
    }

    public function requestActivity(User $user, int $id): Response
    {
        $accessToken = $this->getValidAccessToken($user);

        return Http::withToken($accessToken)->get($this->url('athlete/activities/' . $id));
    }

    public function requestAthleteActivities(User $user, int $page, int $perPage, ?Carbon $from, ?Carbon $to): Response
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

        /** @var Response */
        $accessTokenResponse = $this->requestAccessTokenByRefreshToken($refreshToken);

        $refreshToken->token = $accessTokenResponse->object()->refresh_token;
        $refreshToken->save();
        $accessToken->token = $accessTokenResponse->object()->access_token;
        $accessToken->save();

        return $accessTokenResponse->object()->access_token;
    }

    private function url(string $uri): string
    {
        return env('STRAVA_URL') . $uri;
    }
}
