<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'user_id', 'type', 'points', 'description',
        'pointable_type', 'pointable_id',
        'tenant_id',
    ];

    // Point type constants
    const TYPE_DEVOTION_UPLOAD  = 'devotion_upload';
    const TYPE_SHARING_FIRMAN   = 'sharing_firman';
    const TYPE_PRAYER_REQUEST   = 'prayer_request';
    const TYPE_ACHIEVEMENT      = 'achievement_earned';

    const POINTS_MAP = [
        self::TYPE_DEVOTION_UPLOAD => 10,
        self::TYPE_SHARING_FIRMAN  => 15,
        self::TYPE_PRAYER_REQUEST  => 3,
        self::TYPE_ACHIEVEMENT     => 20,
    ];

    const LABELS = [
        self::TYPE_DEVOTION_UPLOAD => 'Upload Renungan',
        self::TYPE_SHARING_FIRMAN  => 'Sharing Firman',
        self::TYPE_PRAYER_REQUEST  => 'Kirim Pokok Doa',
        self::TYPE_ACHIEVEMENT     => 'Mendapat Achievement',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pointable()
    {
        return $this->morphTo();
    }

    public function getLabelAttribute(): string
    {
        return self::LABELS[$this->type] ?? $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            self::TYPE_DEVOTION_UPLOAD => 'warning',
            self::TYPE_SHARING_FIRMAN  => 'primary',
            self::TYPE_PRAYER_REQUEST  => 'info',
            self::TYPE_ACHIEVEMENT     => 'success',
            default                    => 'secondary',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            self::TYPE_DEVOTION_UPLOAD => 'fa-book-open-reader',
            self::TYPE_SHARING_FIRMAN  => 'fa-share-from-square',
            self::TYPE_PRAYER_REQUEST  => 'fa-hands-praying',
            self::TYPE_ACHIEVEMENT     => 'fa-trophy',
            default                    => 'fa-circle',
        };
    }

    // Scopes
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }
}
