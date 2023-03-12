<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DataQueryService
{
    public function getYearOverViewByWeek(User $user): ?array
    {
        $lastYear = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();

        $data = DB::table('strava_activities as sa')
            ->join('users as u', 'u.id', '=', 'sa.user_id')
            ->join('strava_sport_types as sst', 'sst.id', '=', 'sa.type_id')
            ->select(
                DB::raw('MONTHNAME(sa.start_date) as month'),
                'sst.type',
                DB::raw('SUM(sa.distance) / 1000 as totalDistance'),
                DB::raw('SUM(sa.moving_time) as totalTime')
            )
            ->where('sa.start_date', '>', $lastYear)
            ->where('sa.user_id', '=', $user->id)
            ->groupBy('sst.type', 'month')
            ->get()
            ->toArray();

        if (empty($data)) {
            return null;
        }

        $oneYearPeriod = CarbonPeriod::create($lastYear, '1 month', Carbon::now());
        $monthData = array_map(fn (Carbon $t) => $t->format('F'), $oneYearPeriod->toArray());

        $monthKeys = array_flip($monthData);

        $aggregateInTime = [];
        $aggregateInDistance = [];

        // prepare the aggregated data
        foreach ($data as $record) {
            $aggregateInTime[$record->type] = ['label' => $record->type, 'data' => array_map(fn () => 0, $monthKeys)];
            $aggregateInDistance[$record->type] = ['label' => $record->type, 'data' => array_map(fn () => 0, $monthKeys)];
        }

        foreach ($data as $record) {
            $aggregateInTime[$record->type]['data'][$record->month] = $record->totalTime;
            $aggregateInDistance[$record->type]['data'][$record->month] = $record->totalDistance;
        }

        return [
            'data_in_time' => $aggregateInTime,
            'data_in_distance' => $aggregateInDistance,
            'labels' => array_values($monthData)
        ];
    }

    public function getYearProgress(User $user)
    {
        $data = DB::table('strava_activities')
            ->select(
                'sst.group',
                DB::raw('SUBSTRING(start_date, 1, 10) as onDay'),
                DB::raw('SUM(distance) / 1000 as totalDistance'),
                DB::raw('SUM(moving_time) as totalTime')
            )
            ->join('strava_sport_types as sst', 'sst.id', '=', 'strava_activities.type_id')
            ->where('user_id', '=', $user->id)
            ->groupBy('sst.group', 'onDay')
            ->orderBy('onDay')
            ->get()
            ->keyBy(fn ($r) => $r->group . '_' . $r->onDay);

        if ($data->isEmpty()) {
            return null;
        }

        // When expanding all sport types
        $sportTypes = $data->unique('group')->pluck('group')->toArray();

        $first = Carbon::createFromDate($data->first()->onDay)->startOfYear();
        $last = Carbon::createFromDate($data->last()->onDay)->startOfYear();
        $allYearsPeriod = CarbonPeriod::create($first, '1 year', $last);

        $aggregateInTime = [];
        $aggregateInDistance = [];

        foreach ($sportTypes as $type) {
            foreach ($allYearsPeriod as $year) {
                $allDaysInYear = CarbonPeriod::create($year->startOfYear(), '1 day', 365);

                $aggregateInTime[$type][$year->year] = [
                    'label' => $year->year,
                    'pointRadius' => 0,
                ];
                $aggregateInDistance[$type][$year->year] = [
                    'label' => $year->year,
                    'pointRadius' => 0,
                ];

                $totalTime = 0;
                $totalDistance = 0;

                foreach ($allDaysInYear as $i => $day) {
                    if (!$day->isPast()) {
                        continue;
                    }
                    $dayStr = $day->toDateString();

                    // When expanding all sport types
                    $key = $type . '_' . $dayStr;
                    if ($data->has($key)) {
                        $totalTime += $data[$key]->totalTime;
                        $totalDistance += $data[$key]->totalDistance;
                    }

                    $aggregateInTime[$type][$year->year]['data'][$i] = $totalTime;
                    $aggregateInDistance[$type][$year->year]['data'][$i] = $totalDistance;
                }
            }
        }

        return [
            'labels' => array_keys(array_fill(1, 365, 0)),
            'data_in_distance' => $aggregateInDistance,
            'data_in_time' => $aggregateInTime,
            'sport_types' => $sportTypes,
        ];
    }

    public function getYearContribution(User $user): array
    {
        $lastYear = Carbon::now()->subWeeks(53)->startOfWeek()->toDateString();
    
        $today = Carbon::now();

        $data = DB::table('strava_activities')
            ->select(
                DB::raw('SUBSTRING(start_date, 1, 10) as onDay'),
                DB::raw('SUM(distance) / 1000 as totalDistance'),
                DB::raw('SUM(moving_time) as totalTime')
            )
            ->whereUserId($user->id)
            ->groupBy('onDay')
            ->orderBy('onDay')
            ->get()
            ->keyBy('onDay');


        $computedDataLastYear = [];
        $weeksInLastYear = CarbonPeriod::create($lastYear, '1 week', Carbon::now());
        foreach ($weeksInLastYear as $i => $week) {
            $daysInWeek = CarbonPeriod::create($week->startOfWeek(), '1 day', 7);
            foreach ($daysInWeek as $j => $day) {
                if (!$day->isPast()) continue;

                $dayStr = $day->toDateString();
                $computedDataLastYear[$i][$j] = [
                    'day' => $dayStr,
                    'grade' => $data->has($dayStr) ? $this->getActivityGrade($data[$dayStr]) : 0
                ];
            }
        }

        $computedDataByYear = [];
        $firstDay = new Carbon($data->first()->onDay);
        $allYears = CarbonPeriod::create($firstDay->startOfYear(), '1 year', Carbon::now());
        foreach ($allYears as $year) {
            $weeksInYear = CarbonPeriod::create($year->startOfYear(), '1 week', 52);
            $computedDataByYear = [ 'year' => $year->year, 'data' => []];
            foreach ($weeksInYear as $i => $week) {
                $daysInWeek = CarbonPeriod::create($week->startOfWeek(), '1 day', 7);
                foreach ($daysInWeek as $j => $day) {
                    if (!$day->isPast()) continue;

                    $dayStr = $day->toDateString();
                    $computedDataByYear[$year->year]['data'][$i][$j] = [
                        'day' => $dayStr,
                        'grade' => $data->has($dayStr) ? $this->getActivityGrade($data[$dayStr]) : 0
                    ];
                }
            }
        }

        return ['byYear' => $computedDataByYear, 'lastYear' => $computedDataLastYear];
    }

    public function getStravaDescriptionDate(User $user, Carbon $begin, string $sportTypeGroup): Collection
    {
        return DB::table('strava_activities')
            ->join('strava_sport_types as sst', 'sst.id', '=', 'strava_activities.type_id')
            ->where('user_id', '=', $user->id)
            ->where('start_date', '>=', $begin)
            ->where('sst.group', '=', $sportTypeGroup)
            ->get();
    }

    private function getActivityGrade(object $data): int
    {
        $activityMinutes = $data->totalTime / 60;

        if ($activityMinutes < 40) return 2;
        if ($activityMinutes < 90) return 4;
        if ($activityMinutes < 180) return 6;
        return 8;
    }
}
