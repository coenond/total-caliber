<?php

namespace App\Jobs;

use App\Models\StravaProfile;
use App\Models\User;
use App\Services\StravaActivityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateStravaActivityFromWebhook implements ShouldQueue
{
    private const PAGE_LIMIT = 100;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $athleteId,
        private int $activityId
    ) { }

    public function handle(StravaActivityService $activityService): void
    {
        $user = StravaProfile::whereStravaId($this->athleteId)->firstOrFail()->user;
        $activityService->getOneFromStravaAndStore($user, $this->activityId);
    }
}
