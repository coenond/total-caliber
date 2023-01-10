<?php

namespace App\Jobs;

use App\Models\StravaProfile;
use App\Models\UserGoal;
use App\Models\UserStravaDescription;
use App\Services\StravaActivityService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;p
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

    public function handle(StravaActivityService $activityService): void
    {
        $user = StravaProfile::whereStravaId($this->athleteId)->firstOrFail()->user;
        $activity = $activityService->getOneFromStrava($user, $this->activityId);

        // Currently only support one goal per user
        $userGoal = UserGoal::whereUserId($user->id)->with('sportTypes')->first();
        if (!$userGoal) return;
        if (!$userGoal->sportTypes->contains('type', $activity['sport_type'])) return;

        $descriptionSettings = UserStravaDescription::whereUserId($user->id)->first();
        if (!$descriptionSettings) return;
        if (!$descriptionSettings->enabled) return;

        $appendedDescription = $activity['description'] . '

>> Total Caliber Report <<';

        if ($descriptionSettings->totals) {
            $appendedDescription += '
  Totals:
   - 22 runs: 321.2km in 25h 32min
   - 3 rides: 132.2km in 4h 12min';
        }

        if ($descriptionSettings->week_stats) {
            $appendedDescription += '
  Total this week:
   - 1 run, 12.3km, 1h 31min
   - 1 ride: 30.8km in 1h 3min';
        }

        if ($descriptionSettings->month_stats) {
            $appendedDescription += '
  Month stats:
   - 12 runs, 88.3km, 1h 32
   - 2 rides: 132.2km in 4h 12min';
        }

        $appendedDescription += '
Training from 15 nov. 2022 towards my goal on 2 april 2023
>> by https://totalcaliber.com/';
    }
}
