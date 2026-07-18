<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prayer extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'judul', 'isi_doa', 'pengirim', 'status',
        'is_anonymous', 'catatan_admin',
        'approved_at', 'answered_at',
        'user_id', 'approved_by',
        'tenant_id',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'approved_at'  => 'datetime',
        'answered_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isAnswered(): bool { return $this->status === 'answered'; }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) return 'Anonim';
        return $this->pengirim ?? $this->user->name ?? 'Anonim';
    }

    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopeAnswered($q) { return $q->where('status', 'answered'); }
}
