<?php

namespace App\Events;

use App\Models\GameSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class GameEnded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public GameSession $session) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('game-session.' . $this->session->code)];
    }

    public function broadcastAs(): string
    {
        return 'ended';
    }

    public function broadcastWith(): array
    {
        $sc = $this->session->score_challenger;
        $so = $this->session->score_opponent;

        return [
            'score_challenger' => $sc,
            'score_opponent'   => $so,
            'winner_id'        => $sc > $so
                ? $this->session->challenger_id
                : ($so > $sc ? $this->session->opponent_id : null),
        ];
    }
}
