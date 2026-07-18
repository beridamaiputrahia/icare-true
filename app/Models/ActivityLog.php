<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'user_id', 'type', 'description',
        'subject_id', 'subject_type',
        'tenant_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public static function record(int $userId, string $type, string $description, Model $subject = null): self
    {
        return static::create([
            'user_id'      => $userId,
            'type'         => $type,
            'description'  => $description,
            'subject_id'   => $subject?->getKey(),
            'subject_type' => $subject ? get_class($subject) : null,
        ]);
    }
}
