<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'code', 'challenger_id', 'opponent_id', 'game_type',
        'status', 'score_challenger', 'score_opponent',
        'seed', 'started_at', 'finished_at',
        'tenant_id',
    ];

    protected $casts = [
        'seed'        => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function challenger()
    {
        return $this->belongsTo(User::class, 'challenger_id');
    }

    public function opponent()
    {
        return $this->belongsTo(User::class, 'opponent_id');
    }

    public function roleOf(int $userId): string
    {
        return $this->challenger_id === $userId ? 'challenger' : 'opponent';
    }

    public function scoreOf(int $userId): int
    {
        return $this->roleOf($userId) === 'challenger'
            ? $this->score_challenger
            : $this->score_opponent;
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
