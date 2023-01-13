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
    private const SECONDS_IN_HOUR = 3600;
    private const SECONDS_IN_MINUTE= 60;

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
        string $baseDescription = null
    ): string {
        /** @var Collection */
        $activityTypes = $userGoal->sportTypes->pluck('group')->unique();

        $desc =  $this->title($baseDescription);

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

        $hours = (int)($timeInSeconds / self::SECONDS_IN_HOUR);
        $minutes = (int)(($timeInSeconds % self::SECONDS_IN_HOUR) / self::SECONDS_IN_MINUTE);
        $time = "{$hours}h {$minutes}min";

        $distance = round($distanceInMeters / self::METERS_IN_KM, 1);

        return "
  - {$count} {$type}: {$distance}km in {$time}";
    }

    private function title(string $baseDescription = null): string
    {
        $title = '>> Total Caliber Report <<';

        return empty($baseDescription) ? $title : $baseDescription . '
'.$title;
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
Training from '.$start.' towards '.$userGoal->name.' on '.$end.'
>> by https://totalcaliber.com/';
    }
}
