<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSessionParticipant extends Model
{
    protected $fillable = ['game_session_id', 'user_id', 'status', 'score', 'finished'];

    protected $casts = [
        'finished' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
