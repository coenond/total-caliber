<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function stravaAuthToken(): HasOne
    {
        return $this->hasOne(StravaAuthToken::class);
    }

    public function stravaSyncJobs(): HasMany
    {
        return $this->hasMany(StravaSyncJob::class);
    }

    public function goal(): HasOne
    {
        return $this->hasOne(UserGoal::class);
    }

    public function stravaDescription(): HasOne
    {
        return $this->hasOne(UserStravaDescription::class);
    }

    public function stravaProfile(): HasOne
    {
        return $this->hasOne(StravaProfile::class);
    }

    public function hasSyncJobOnCoolDown(): bool
    {
        $lastSyncJob = $this->stravaSyncJobs()->orderByDesc('created_at')->first();
        return $lastSyncJob && $lastSyncJob->created_at->diffInHours() < 1;
    }
}
