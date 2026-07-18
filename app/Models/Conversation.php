<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use BelongsToTenant;
    protected $fillable = ['type', 'name', 'tenant_id'];

    public const TYPE_GLOBAL  = 'global';
    public const TYPE_LEADER  = 'leader';
    public const TYPE_PRIVATE = 'private';

    public static function globalForTenant(): ?self
    {
        return static::where('type', self::TYPE_GLOBAL)->first();
    }

    public static function leaderForTenant(): ?self
    {
        return static::where('type', self::TYPE_LEADER)->first();
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function getUnreadCountForUser(User $user): int
    {
        $lastRead = $this->participants()
            ->where('user_id', $user->id)
            ->first()?->pivot->last_read_at;

        $query = $this->messages()->where('user_id', '!=', $user->id);

        if ($lastRead) {
            $query->where('created_at', '>', $lastRead);
        }

        return $query->count();
    }

    public function markReadFor(User $user): void
    {
        $this->participants()->updateExistingPivot($user->id, [
            'last_read_at' => now(),
        ]);
    }
}
