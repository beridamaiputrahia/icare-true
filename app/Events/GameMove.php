<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class GameMove implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public GameSession $session,
        public int         $userId,
        public array       $payload,  // move data: {type, value, ...}
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('game-session.' . $this->session->code)];
    }

    public function broadcastAs(): string
    {
        return 'move';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id'          => $this->userId,
            'role'             => $this->session->roleOf($this->userId),
            'score_challenger' => $this->session->score_challenger,
            'score_opponent'   => $this->session->score_opponent,
            'payload'          => $this->payload,
        ];
    }
}
