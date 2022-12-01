<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserStravaDescription;

class StravaDescriptionService
{
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
}
