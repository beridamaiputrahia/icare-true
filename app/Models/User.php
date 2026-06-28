<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active',
        'total_points', 'level', 'is_online', 'last_seen', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'is_online'         => 'boolean',
            'last_seen'         => 'datetime',
            'total_points'      => 'integer',
            'level'             => 'integer',
        ];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }

    // ── Core Relationships ─────────────────────────────────────────
    public function member()        { return $this->hasOne(Member::class); }
    // Fallback for legacy members without user_id
    public function memberByName()  { return $this->hasOne(Member::class, 'nama_lengkap', 'name')->whereNull('user_id'); }
    public function schedules()    { return $this->hasMany(Schedule::class, 'created_by'); }
    public function announcements(){ return $this->hasMany(Announcement::class, 'created_by'); }
    public function members()      { return $this->hasMany(Member::class, 'created_by'); }
    public function dailyVerses()  { return $this->hasMany(DailyVerse::class, 'created_by'); }
    public function devotions()    { return $this->hasMany(Devotion::class, 'user_id'); }
    public function prayers()      { return $this->hasMany(Prayer::class, 'user_id'); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class)->latest(); }

    // ── Achievement Relationships ───────────────────────────────────
    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('count_at_award', 'awarded_at')
                    ->withTimestamps()
                    ->orderBy('required_count');
    }

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    // ── Phase 4 Relationships ──────────────────────────────────────
    public function points()
    {
        return $this->hasMany(UserPoint::class)->latest();
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // ── Achievement Helpers ─────────────────────────────────────────
    public function getApprovedDevotionsCountAttribute(): int
    {
        return $this->devotions()->where('status', 'approved')->count();
    }

    public function getTotalDevotionsCountAttribute(): int
    {
        return $this->devotions()->count();
    }

    public function getHighestAchievementAttribute(): ?Achievement
    {
        return $this->achievements()
                    ->orderBy('required_count', 'desc')
                    ->first();
    }

    public function hasAchievement(string $slug): bool
    {
        return $this->achievements()->where('slug', $slug)->exists();
    }

    // ── Banner ──────────────────────────────────────────────────────
    public function getBannerAttribute(): ?Banner
    {
        return app(\App\Services\BannerService::class)->resolveForUser($this);
    }

    // ── Level & Points ──────────────────────────────────────────────
    public function getLevelNameAttribute(): string
    {
        return app(\App\Services\PointService::class)->getLevelName($this->level);
    }

    public function getLevelProgressAttribute(): array
    {
        return app(\App\Services\PointService::class)->getLevelProgress($this);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? \Illuminate\Support\Facades\Storage::url($this->avatar) : null;
    }

    public function markOnline(): void
    {
        $this->update(['is_online' => true, 'last_seen' => now()]);
    }

    public function markOffline(): void
    {
        $this->update(['is_online' => false, 'last_seen' => now()]);
    }

    // ── Progress ────────────────────────────────────────────────────
    public function getSharingFirmanProgressAttribute(): array
    {
        $count = $this->approved_devotions_count;
        $milestones = [3 => 'Domba Kecil', 6 => 'Murid', 9 => 'Pewarta', 12 => 'Penginjil Sejati'];
        $next = null;
        foreach ($milestones as $req => $label) {
            if ($count < $req) { $next = ['required' => $req, 'label' => $label]; break; }
        }
        return [
            'count'    => $count,
            'next'     => $next,
            'percent'  => $next ? min(100, round(($count / $next['required']) * 100)) : 100,
        ];
    }

    public function getRenunganProgressAttribute(): array
    {
        $count = $this->total_devotions_count;
        $milestones = [3 => 'Seeder', 5 => 'Growther', 8 => 'Pencerita', 12 => 'Panglima Injil'];
        $next = null;
        foreach ($milestones as $req => $label) {
            if ($count < $req) { $next = ['required' => $req, 'label' => $label]; break; }
        }
        return [
            'count'   => $count,
            'next'    => $next,
            'percent' => $next ? min(100, round(($count / $next['required']) * 100)) : 100,
        ];
    }
}
