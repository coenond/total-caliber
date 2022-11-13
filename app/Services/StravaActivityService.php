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
    public function getFromStrava(
        User $user,
        int $page = 1,
        int $perPage = 30,
        ?Carbon $from = null,
        ?Carbon $to = null
    ): array {
        $result = $this->client->getActivities($user, $page, $perPage, $from, $to);
        return (array) $result->object();
    }
}