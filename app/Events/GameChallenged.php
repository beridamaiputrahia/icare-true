<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class GameChallenged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public GameSession $session) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('game-user.' . $this->session->opponent_id)];
    }

    public function broadcastAs(): string
    {
        return 'challenged';
    }

    public function broadcastWith(): array
    {
        return [
            'session_code'     => $this->session->code,
            'game_type'        => $this->session->game_type,
            'challenger_name'  => $this->session->challenger->name,
            'challenger_id'    => $this->session->challenger_id,
        ];
    }
}
