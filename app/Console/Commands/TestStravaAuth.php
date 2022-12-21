<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StravaClient;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;

class TestStravaAuth extends Command
{
    protected $signature = 'strava:auth:test';
    protected $description = 'Test Strava authorization settings for user';

    public function handle(StravaClient $client): int
    {
        $user = User::findOrFail($this->ask('Provide user_id:'));

        /** @var Response */
        $result = $client->requestAthleteActivities($user, 1, 1, null, null);
        $result->successful()
            ? $this->info('Result: ' . $result->status())
            : $this->error('Result: ' . $result->status());

        return Command::SUCCESS;
    }
}
