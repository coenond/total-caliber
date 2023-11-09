<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserGoal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];

    public function sportTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            StravaSportType::class,
            'user_goal_activity_types',
            'user_goal_id',
            'type_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
