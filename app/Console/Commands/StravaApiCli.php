<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStravaDescription;
use App\Models\StravaActivity;
use App\Models\StravaProfile;
use App\Services\StravaActivityService;
use App\Services\StravaClient;
use Illuminate\Console\Command;

class StravaApiCli extends Command
{
    const ACTION_GET_ACTIVITY = 'getActivity';
    const ACTION_POST_REPORT = 'postReport';

    const ACTIONS = [
        self::ACTION_GET_ACTIVITY,
        self::ACTION_POST_REPORT,
    ];

    protected $signature = 'strava:api';
    protected $description = 'Interact with Strava API';

    /** @var StravaClient */
    private $client;
    /** @var StravaActivityService */
    private $stravaActivityService;

    public function handle(
        StravaClient $client,
        StravaActivityService $stravaActivityService
    ): int {
        $this->client = $client;
        $this->stravaActivityService = $stravaActivityService;

        $action = $this->choice('Select API Action', self::ACTIONS);
        $this->{$action}();
        return Command::SUCCESS;
    }

    protected function getActivity(): void
    {
        $athleteId = $this->ask('Athlete ID:');
        $stravaProfile = StravaProfile::whereStravaId($athleteId)->firstOrFail();

        $activityId = (int) $this->ask('Activity ID:');

        $response = $this->client->requestActivity($stravaProfile->user, $activityId);
        $this->info(print_r((array) $response->object()));
    }

    protected function postReport(): void
    {
        $athleteId = (int) $this->ask('Athlete ID:');
        $stravaProfile = StravaProfile::whereStravaId($athleteId)->with('user')->firstOrFail();
        $user = $stravaProfile->user;

        $activityId = (int) $this->ask('Strava Activity ID:');
        $activity = StravaActivity::whereStravaId($activityId)->whereUserId($stravaProfile->user_id)->firstOrFail();

        $activityFromStrava = $this->stravaActivityService->getOneFromStrava($user, $activityId);

        $this->info('Dispatching update job for:');
        $this->info(print_r([
            'Athlete' => $user->name,
            'Activity' => $activity->name,
            'Description' => $activityFromStrava['description']
        ]));

        if (!$this->confirm('Continue')) return;

        UpdateStravaDescription::dispatch($athleteId, $activityId);
    }
}
