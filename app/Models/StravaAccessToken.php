<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StravaAccessToken extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id', 'token', 'expires_at'];

    public function isNotExpired()
    {
        return !Carbon::createFromTimestamp($this->expires_at)->isPast();
    }
}
