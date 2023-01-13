<?php

namespace App\Jobs;

use App\Enums\StravaSportTypeEnum;
use App\Models\StravaProfile;
use App\Models\StravaSportType;
use App\Models\UserGoal;
use App\Models\UserStravaDescription;
use App\Services\StravaActivityService;
use App\Services\StravaClient;
use App\Services\StravaDescriptionService;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStravaDescription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $athleteId,
        private int $activityId
    ) { }

    public function handle(
        StravaActivityService $activityService,
        StravaDescriptionService $descriptionService,
        StravaClient $stravaClient
    ): void {
        $user = StravaProfile::whereStravaId($this->athleteId)->firstOrFail()->user;
        $activity = $activityService->getOneFromStrava($user, $this->activityId);

        $sportType = StravaSportType::whereType($activity['sport_type'])->firstOrFail();
        if (!in_array($sportType->group, StravaSportTypeEnum::supportedForGoals())) return;

        // Currently only support one goal per user
        $userGoal = UserGoal::whereUserId($user->id)->with('sportTypes')->first();
        if (!$userGoal) return;
        if (!$userGoal->sportTypes->contains('type', $activity['sport_type'])) return;
        $period = CarbonPeriod::create($userGoal->start, $userGoal->end->addDay());
        if (!$period->isInProgress()) return;

        $descriptionSettings = UserStravaDescription::whereUserId($user->id)->first();
        if (!$descriptionSettings) return;
        if (!$descriptionSettings->enabled) return;

        $description = $descriptionService->createPlainTextDescription(
            $user,
            $userGoal,
            $descriptionSettings,
            isset($activity['description']) ? $activity['description'] : null
        );

        $response = $stravaClient->requestUpdateActivityDescription($user, $this->activityId, $description);

        if (!$response->successful()) {
            $status = $response->status();
            $reason = $response->reason();
            throw new Exception("Failed to update Strava description. Got {$status} because: {$reason}");
        }
    }
}
