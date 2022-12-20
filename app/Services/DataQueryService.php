<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DataQueryService
{
    public function getYearOverViewByWeek(User $user): array
    {
       $data = DB::table('strava_activities as sa')
        ->join('users as u', 'u.id', '=', 'sa.user_id')
        ->join('strava_sport_types as sst', 'sst.id', '=', 'sa.type_id')
        ->select(DB::raw('MONTHNAME(sa.start_date) as month'), 'sst.type', DB::raw('SUM(sa.distance) as distance'))
        ->where('sa.start_date', '>', '2022-04-01')
        ->whereIn('sst.type', ['Ride', 'Run'])
        ->groupBy('sst.type', 'month')
        ->get()
        ->toArray();

        $monthData = [
            'January' => 0,  
            'February' => 0, 
            'March' => 0,    
            'April' => 0,    
            'May' => 0,      
            'June' => 0,     
            'July' => 0,     
            'August' => 0,   
            'September' => 0,
            'October' => 0,  
            'November' => 0, 
            'December' => 0, 
        ];

        $aggregate = [
            'Ride' => ['data' => $monthData],
            'Run' => ['data' => $monthData],
        ];

        foreach ($data as $record) {
            // $aggregate[$record->month]['data'][$record->type] = $record->distance;
            $aggregate[$record->type]['data'][$record->month] = $record->distance;
        }

        return $aggregate;
    }
}