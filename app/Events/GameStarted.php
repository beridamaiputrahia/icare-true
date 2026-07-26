<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class GameStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public GameSession $session) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('game-session.' . $this->session->code)];
    }

    public function broadcastAs(): string
    {
        return 'started';
    }

    public function broadcastWith(): array
    {
        return [
            'session_code' => $this->session->code,
            'game_type'    => $this->session->game_type,
            'seed'         => $this->session->seed,
            'players'      => $this->session->participants
                ->where('status', 'accepted')
                ->map(fn ($p) => ['id' => $p->user_id, 'nama' => $p->user->name])
                ->values(),
        ];
    }
}
