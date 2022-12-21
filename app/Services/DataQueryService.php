<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DataQueryService
{
    public function getYearOverViewByWeek(User $user): array
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
}