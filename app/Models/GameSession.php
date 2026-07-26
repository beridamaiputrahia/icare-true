<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'code', 'host_id', 'game_type',
        'status', 'seed', 'started_at', 'finished_at',
        'tenant_id',
    ];

    protected $casts = [
        'seed'        => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function participants()
    {
        return $this->hasMany(GameSessionParticipant::class);
    }

    public function participantOf(int $userId): ?GameSessionParticipant
    {
        return $this->relationLoaded('participants')
            ? $this->participants->firstWhere('user_id', $userId)
            : $this->participants()->where('user_id', $userId)->first();
    }

    public function isParticipant(int $userId): bool
    {
        return $this->participantOf($userId) !== null;
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
