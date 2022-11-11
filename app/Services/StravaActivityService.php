<?php

namespace App\Services;

use App\Models\User;

class StravaActivityService
{
    private CONST stravaUrl = 'https://www.strava.com';

    public function __construct(
        private StravaClient $client
    ) { }

    /**
     * Update or create the user strava authorization code.
     */
    public function getFromStrava(User $user): array
    {
        $result = $this->client->getActivities($user);
        return (array) $result->object();
    }
}