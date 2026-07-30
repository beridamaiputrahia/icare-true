<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'schedule_id',
        'member_id',
        'scanned_by',
        'scanned_at',
        'tenant_id',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scanner()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
