<?php

namespace App\Jobs;

use App\Models\StravaActivity;
use App\Models\StravaProfile;
use App\Models\StravaSportType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStravaActivityFromWebhook implements ShouldQueue
{
    private const PAGE_LIMIT = 100;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $athleteId,
        private int $activityId,
        private array $updates
    ) { }

    public function handle(): void
    {
        $user = StravaProfile::whereStravaId($this->athleteId)->firstOrFail()->user;

        $activity = StravaActivity::whereStravaId($this->activityId)
            ->whereUserId($user->id)
            ->findOrFail();

        if (array_key_exists('title', $this->updates)) {
            $this->updateTitle($activity);
        }

        if (array_key_exists('type', $this->updates)) {
            $this->updateType($activity);
        }

        $activity->save();
    }

    private function updateTitle(StravaActivity &$activity): void
    {
        $activity->title = $this->updates['title'];
    }

    private function updateType(StravaActivity &$activity): void
    {
        $newType = StravaSportType::whereType($this->updates['type'])->firstOrFail();
        $activity->type_id = $newType->id;
    }
}
