<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStravaDescription extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    protected $casts = [
        'enabled' => 'boolean',
        'simple' => 'boolean',
        'totals' => 'boolean',
        'week_stats' => 'boolean',
        'month_stats' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
