<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['type', 'name'];

    public const GLOBAL_ID = 1;
    public const LEADER_ID = 2;

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
