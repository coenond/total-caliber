<?php

namespace App\Services;

use App\Enums\StravaWebhooks\StravaAspectTypeEnum;
use App\Enums\StravaWebhooks\StravaObjectTypeEnum;
use App\Jobs\CreateStravaActivityFromWebhook;
use App\Jobs\DeleteStravaActivityFromWebhook;
use App\Jobs\UpdateStravaActivityFromWebhook;
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

    public function getListFromStrava(
        User $user,
        int $page = 1,
        int $perPage = 30,
        ?Carbon $from = null,
        ?Carbon $to = null
    ): array {
        $result = $this->client->requestAthleteActivities($user, $page, $perPage, $from, $to);
        return (array) $result->object();
    }

    public function getOneFromStrava(
        User $user,
        int $stravaActivityId
    ): array {
        $result = $this->client->requestActivity($user, $stravaActivityId);
        return (array) $result->object();
    }

    public function getOneFromStravaAndStore(
        User $user,
        int $stravaActivityId
    ): StravaActivity {
        $result = $this->getOneFromStrava($user, $stravaActivityId);
        $sportTypes = StravaSportType::all()->keyBy('type');
        return StravaActivity::updateOrCreate([
            'user_id' => $user->id,
            'strava_id' => $stravaActivityId,
        ], [
            'type_id' => $sportTypes[$result['sport_type']]->id,
            'name' => $result['name'],
            'distance' => $result['distance'],
            'moving_time' => $result['moving_time'],
            'total_elevation_gain' => $result['total_elevation_gain'],
            'start_date' => new Carbon($result['start_date']),
            'timezone' => $result['timezone'],
            'calories' => $result['calories'],
            'trainer' => $result['trainer'],
            'commute' => $result['commute'],
            'manual' => $result['manual'],
            'private' => $result['private'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    public function getListFromDb(User $user): Collection {
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

    public function handleNewIncomingActivity(
        StravaAspectTypeEnum $aspectType,
        StravaObjectTypeEnum $objectType,
        int $athleteId,
        int $objectId,
        array $updates
    ): void {
        if ($objectType === StravaObjectTypeEnum::athlete) {
            // handle athlete update.
            return;
        }

        switch ($aspectType) {
            case StravaAspectTypeEnum::create:
                CreateStravaActivityFromWebhook::dispatch($athleteId, $objectId);
                break;
            case StravaAspectTypeEnum::update:
                UpdateStravaActivityFromWebhook::dispatch($athleteId, $objectId, $updates);
                break;
            case StravaAspectTypeEnum::delete:
                DeleteStravaActivityFromWebhook::dispatch($athleteId, $objectId);
                break;
        }

        return;
    }
}