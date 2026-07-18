<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'secondary_role', 'is_active',
        'total_points', 'level', 'is_online', 'last_seen', 'avatar',
        'tenant_id',
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

    // ── Role Constants ─────────────────────────────────────────────
    const ROLE_ADMIN   = 'admin';
    const ROLE_ICL     = 'icl';
    const ROLE_CTL     = 'ctl';
    const ROLE_ANGGOTA = 'anggota';

    public function isAdmin(): bool   { return $this->role === self::ROLE_ADMIN; }
    public function isICL(): bool     { return $this->role === self::ROLE_ICL; }
    public function isCTL(): bool     { return $this->role === self::ROLE_CTL; }
    public function isAnggota(): bool { return $this->role === self::ROLE_ANGGOTA; }

    /** Apakah user punya akses setingkat leader ke atas (admin/ICL/CTL) */
    public function isLeader(): bool  { return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_ICL, self::ROLE_CTL]); }

    /**
     * Cek apakah user memiliki role tertentu — mencakup primary DAN secondary role.
     * Digunakan middleware & blade untuk cek akses gabungan.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role || $this->secondary_role === $role;
    }

    /** Cek apakah user memiliki salah satu dari beberapa role */
    public function hasAnyRole(string ...$roles): bool
    {
        return in_array($this->role, $roles) || in_array($this->secondary_role, $roles);
    }

    /** Semua role yang dimiliki user (array, max 2 elemen) */
    public function allRoles(): array
    {
        $roles = [$this->role];
        if ($this->secondary_role) {
            $roles[] = $this->secondary_role;
        }
        return $roles;
    }

    /** Label secondary role, null jika tidak ada */
    public function secondaryRoleLabel(): ?string
    {
        return match($this->secondary_role) {
            self::ROLE_ICL     => 'ICL',
            self::ROLE_CTL     => 'CTL',
            self::ROLE_ANGGOTA => 'Anggota',
            default            => null,
        };
    }

    /** Label tampilan role utama */
    public function roleLabel(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN   => 'Admin',
            self::ROLE_ICL     => 'ICL',
            self::ROLE_CTL     => 'CTL',
            default            => 'Anggota',
        };
    }

    /** Kelas Bootstrap badge untuk role utama */
    public function roleColor(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN   => 'danger',
            self::ROLE_ICL     => 'warning',
            self::ROLE_CTL     => 'info',
            default            => 'primary',
        };
    }

    // ── Tenant ─────────────────────────────────────────────────────
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

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
