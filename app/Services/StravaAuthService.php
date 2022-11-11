<?php

namespace App\Services;

use App\Models\StravaAccessToken;
use App\Models\StravaAuthToken;
use App\Models\StravaRefreshToken;
use App\Models\User;

class StravaAuthService
{
    private CONST stravaUrl = 'https://www.strava.com';

    public function __construct(
        private StravaClient $client
    ) { }


    public function getAuthorizationUrl(): string
    {
        return self::stravaUrl . '/oauth/authorize?' . http_build_query([
            'client_id' => env('STRAVA_CLIENT_ID'),
            'response_type' => 'code',
            'approval_prompt' => 'force',
            'scope' => 'activity:read,activity:write,activity:read_all',
            'redirect_uri' => url('/strava/authorize')
        ]);
    }

    /**
     * Update or create the user strava authorization code.
     */
    public function storeUserStravaCode(User $user, string $code, string $scopes): StravaAuthToken
    {
        return StravaAuthToken::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code, 'scopes' => $scopes]
        );
    }

    public function setUserInitialRefreshToken(StravaAuthToken $authToken)
    {
        // Invalidate all old tokens - if any
        StravaRefreshToken::whereUserId($authToken->user_id)->update(['active' => 0]);

        $result = $this->client->getAccessToken($authToken);

        if ($result->successful()) {
            StravaRefreshToken::updateOrCreate(
                [ 'user_id' => $authToken->user_id ],
                [ 'token' => $result->object()->refresh_token ]
            );

            StravaAccessToken::updateOrCreate(
                [ 'user_id' => $authToken->user_id ],
                [ 'token' => $result->object()->access_token, 'expires_at' => $result->object()->expires_at ]
            );
        }
    }

    public function userHasStravaAuth(User $user): bool
    {
        return  StravaRefreshToken::whereUserId($user->id)->exists();
    }
}