<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserGoal extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function sportTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            StravaSportType::class,
            'user_goal_activity_types',
            'user_goal_id',
            'type_id',
        );
    }
}
