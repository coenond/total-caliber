<?php

namespace App\Jobs;

use App\Models\StravaActivity;
use App\Models\StravaProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteStravaActivityFromWebhook implements ShouldQueue
{
    private const PAGE_LIMIT = 100;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $athleteId,
        private int $activityId
    ) { }

    public function handle(): void
    {
        $stravaProfile = StravaProfile::whereStravaId($this->athleteId)->firstOrFail();
        $activity = StravaActivity::whereStravaId($this->activityId)
            ->whereUserId($stravaProfile->user_id)
            ->first();
        
        if (empty($activity)) {
            return;
        }

        $activity->delete();
    }
}
