<?php

namespace App\Console\Commands;

use App\Models\StravaProfile;
use App\Services\StravaClient;
use Illuminate\Console\Command;

class StravaApiCli extends Command
{
    const ACTION_GET_ACTIVITY = 'getActivity';
    const ACTIONS = [
        self::ACTION_GET_ACTIVITY,
    ];

    protected $signature = 'strava:api';
    protected $description = 'Interact with Strava API';

    /** @var StravaClient */
    private $client;

    public function handle(StravaClient $client): int
    {
        $this->client = $client;
        $action = $this->choice('Select API Action', self::ACTIONS);
        $this->{$action}();

        return Command::SUCCESS;
    }

    private function getActivity(): void
    {
       $athleteId = $this->ask('Athlete ID:');
       $stravaProfile = StravaProfile::whereStravaId($athleteId)->firstOrFail();

       $activityId = (int) $this->ask('Activity ID:');

       $response = $this->client->requestActivity($stravaProfile->user, $activityId);
       
       $this->info(print_r((array) $response->object()));
    }
}
