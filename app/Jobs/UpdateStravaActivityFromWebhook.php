<?php

namespace App\Jobs;

use App\Models\StravaActivity;
use App\Models\StravaProfile;
use App\Models\StravaSportType;
use App\Services\StravaActivityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStravaActivityFromWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $athleteId,
        private int $activityId,
        private array $updates
    ) { }

    public function handle(StravaActivityService $activityService): void
    {
        $stravaProfile = StravaProfile::whereStravaId($this->athleteId)->firstOrFail();

        if (array_key_exists('title', $this->updates)) {
            $this->updateTitle($stravaProfile);
        }

        /**
         * The webhook doesn't give the dedicated "sport type". So activity has to be fetched from
         * Strava to update to the right type. 
         */
        if (array_key_exists('type', $this->updates)) {
            $user = $stravaProfile->user;
            $activityService->getOneFromStravaAndStore($user, $this->activityId);
        }
    }

    private function updateTitle(StravaProfile $stravaProfile): void
    {
        $activity = StravaActivity::whereStravaId($this->activityId)
            ->whereUserId($stravaProfile->user_id)
            ->firstOrFail();
        $activity->name = $this->updates['title'];
        $activity->save();
    }
}
