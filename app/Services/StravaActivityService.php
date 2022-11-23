<?php

namespace App\Services;

use App\Models\StravaActivity;
use App\Models\StravaSportType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StravaActivityService
{

    public function __construct(
        private StravaClient $client
    ) { }

    /**
     * Update or create the user strava authorization code.
     */
    public function getFromStrava(
        User $user,
        int $page = 1,
        int $perPage = 30,
        ?Carbon $from = null,
        ?Carbon $to = null
    ): array {
        $result = $this->client->requestAthleteActivities($user, $page, $perPage, $from, $to);
        return (array) $result->object();
    }

    public function getFromDb(User $user): Collection {
        return StravaActivity::whereUserId($user->id)->get();
    }

    public function storeActivitiesFromRaw(User $user, array $rawActivities): void
    {
        $allActivityIds = StravaActivity::whereUserId($user->id)->pluck('strava_id')->toArray();
        $filteredData = array_filter($rawActivities, fn($act) => !in_array($act->id, $allActivityIds));
        $sportTypes = StravaSportType::all()->keyBy('type');

        $inserts = array_map(fn ($activity) => [
            'user_id' => $user->id,
            'strava_id' => $activity->id,
            'type_id' => $sportTypes[$activity->sport_type]->id,
            'name' => $activity->name,
            'distance' => $activity->distance,
            'moving_time' => $activity->moving_time,
            'total_elevation_gain' => $activity->total_elevation_gain,
            'start_date' => new Carbon($activity->start_date),
            'timezone' => $activity->timezone,
            'calories' => 0,
            'trainer' => $activity->trainer,
            'commute' => $activity->commute,
            'manual' => $activity->manual,
            'private' => $activity->private,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ], $filteredData);

        StravaActivity::insert($inserts);
    }
}