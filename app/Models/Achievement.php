<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name', 'slug', 'category', 'description',
        'icon', 'color', 'badge_label',
        'required_count', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
                    ->withPivot('count_at_award', 'awarded_at')
                    ->withTimestamps();
    }

    public static function getSharingFirmanAchievements()
    {
        return static::where('category', 'sharing_firman')
                     ->where('is_active', true)
                     ->orderBy('required_count')
                     ->get();
    }

    public static function getRenunganAchievements()
    {
        return static::where('category', 'renungan')
                     ->where('is_active', true)
                     ->orderBy('required_count')
                     ->get();
    }
}
