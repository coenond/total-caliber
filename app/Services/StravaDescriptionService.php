<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserGoal;
use App\Models\UserStravaDescription;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StravaDescriptionService
{
    private const METERS_IN_KM = 1000;
    
    public function __construct(
        private DataQueryService $queryService
    ) { }

    public function updateOrCreate(
        User $user,
        bool $totals,
        bool $weekStats,
        bool $monthStats,
    ): void {
        UserStravaDescription::updateOrCreate(['user_id' => $user->id], [
            'totals' => $totals,
            'week_stats' => $weekStats,
            'month_stats' => $monthStats,
        ]);
    }

    public function createPlainTextDescription(
        User $user,
        UserGoal $userGoal,
        UserStravaDescription $descriptionSettings,
        string $baseDescription
    ): string {
        /** @var Collection */
        $activityTypes = $userGoal->sportTypes->pluck('group')->unique();

        $desc = $baseDescription . $this->title();

        if ($descriptionSettings->totals) {
            $desc .= $this->totalsTitle();
            foreach ($activityTypes as $type) {
                $data = $this->queryService->getStravaDescriptionDate($user, $userGoal->start, $type);
                $line = $this->createDataLine($data->count(), $type, $data->sum('distance'), $data->sum('moving_time'));
                $desc .= $line;
            }
        }

        if ($descriptionSettings->week_stats) {
            $desc .= $this->weekTitle();
            foreach ($activityTypes as $type) {
                $data = $this->queryService->getStravaDescriptionDate($user, Carbon::now()->startOfWeek(), $type);
                $line = $this->createDataLine($data->count(), $type, $data->sum('distance'), $data->sum('moving_time'));
                $desc .= $line;
            }
        }

        if ($descriptionSettings->month_stats) {
            $desc .= $this->monthTitle();
            foreach ($activityTypes as $type) {
                $data = $this->queryService->getStravaDescriptionDate($user, Carbon::now()->startOfMonth(), $type);
                $line = $this->createDataLine($data->count(), $type, $data->sum('distance'), $data->sum('moving_time'));
                $desc .= $line;
            }
        }

        $desc .= $this->footer($userGoal);

        return $desc;
    }

   private function createDataLine(int $count, string $type, float $distanceInMeters, int $timeInSeconds): string
    {
        if ($count === 0) {
                    return "
  - {$count} {$type}s";
        }
        $type = $count > 1 ? $type.'s' : $type;
        $time = gmdate('H\h i\m\i\n', $timeInSeconds);
        $distance = round($distanceInMeters / self::METERS_IN_KM, 1);

        return "
  - {$count} {$type}: {$distance}km in {$time}";
    }

    private function title(): string
    {
        return '
>> Total Caliber Report <<';
    }
    private function totalsTitle(): string
    {
        return '
 Totals:';
    }
    private function weekTitle(): string
    {
        return '
 This week:';
    }
    private function monthTitle(): string
    {
        return '
 This month:';
    }
    private function footer(UserGoal $userGoal): string
    {
        $start = $userGoal->start->toFormattedDateString();
        $end = $userGoal->end->toFormattedDateString();

        return '
Training from '.$start.' towards my goal on '.$end.'
>> by https://totalcaliber.com/';
    }
}
