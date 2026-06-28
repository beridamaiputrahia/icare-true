<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'body', 'is_deleted'];

    protected $casts = ['is_deleted' => 'boolean'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    public function getDisplayBodyAttribute(): string
    {
        return $this->is_deleted ? '[Pesan telah dihapus]' : $this->body;
    }
}
