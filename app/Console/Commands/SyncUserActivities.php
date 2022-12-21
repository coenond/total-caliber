<?php

namespace App\Console\Commands;

use App\Jobs\SyncStravaActivities;
use App\Models\StravaSyncJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncUserActivities extends Command
{
    protected $signature = 'strava:sync';
    protected $description = 'Command description';

    public function handle(): int
    {
        $user = User::findOrFail($this->ask('Provide user_id:'));
        $stravaJobModel = StravaSyncJob::create(['user_id' => $user->id]);

        $years = $this->ask('For how many years in the past?', 10);
        SyncStravaActivities::dispatch($user, $stravaJobModel, 1, Carbon::now()->subYears($years), Carbon::now());

        return Command::SUCCESS;
    }
}
