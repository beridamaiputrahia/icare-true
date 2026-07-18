<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'user_id', 'achievement_id', 'count_at_award', 'awarded_at',
        'tenant_id',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
