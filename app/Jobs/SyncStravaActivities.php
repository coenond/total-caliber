<?php

namespace App\Jobs;

use App\Models\StravaSyncJob;
use App\Models\User;
use App\Services\StravaActivityService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncStravaActivities implements ShouldQueue
{
    private const PAGE_LIMIT = 200;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private User $user,
        private StravaSyncJob $stravaSyncJob,
        private int $iteration,
        private Carbon $activitiesFrom,
        private Carbon $activitiesTo
    ) { }

    public function handle(StravaActivityService $activityService): void
    {
        $activities = $activityService->getListFromStrava(
            $this->user,
            $this->iteration,
            self::PAGE_LIMIT,
            $this->activitiesFrom,
            $this->activitiesTo
        );

        $activityService->storeActivitiesFromRaw($this->user, $activities);

        count($activities) === self::PAGE_LIMIT
            ? $this->dispatchNewJob()
            : $this->finishJob();
    }

    private function dispatchNewJob(): void
    {
        SyncStravaActivities::dispatch(
            $this->user,
            $this->stravaSyncJob,
            $this->iteration + 1,
            $this->activitiesFrom,
            $this->activitiesTo
        );
    }

    private function finishJob(): void
    {
        $this->stravaSyncJob->iterations = $this->iteration;
        $this->stravaSyncJob->completed_at = Carbon::now();
        $this->stravaSyncJob->save();
    }
}
