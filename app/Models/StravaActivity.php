<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StravaActivity extends Model
{
    use HasFactory;

    private const KM_TO_MILES_RATE = 0.62137;

    protected $guarded = ['id'];
    protected $appends = ['readableDistanceInKm', 'readableDistanceInMiles', 'readableTime'];

    public function sportType(): HasOne
    {
        return $this->hasOne(StravaSportType::class, 'id', 'type_id');
    }

    protected function getReadableDistanceInKmAttribute(): string
    {
        if ($this->distance === 0.00) {
            return '---';
        }
        return number_format($this->distance / 1_000, 2) . ' km';
    }

    public function getReadableDistanceInMilesAttribute(): string
    {
        if ($this->distance === 0.00) {
            return '---';
        }
        return number_format(($this->distance / 1_000) * self::KM_TO_MILES_RATE, 2) . ' miles';
    }

    public function getReadableTimeAttribute(): string
    {
        $s = $this->moving_time;
        return sprintf('%02d:%02d:%02d', ($s / 3_600), ($s / 60 % 60), $s % 60);
    }
}
