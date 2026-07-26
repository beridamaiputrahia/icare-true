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
        $players = $this->session->participants->where('status', 'accepted');
        $topScore = $players->max('score');

        return [
            'players' => $players->map(fn ($p) => [
                'user_id' => $p->user_id,
                'nama'    => $p->user->name,
                'score'   => $p->score,
            ])->values(),
            // Bisa lebih dari satu id kalau skor tertinggi seri.
            'winner_ids' => $players->where('score', $topScore)->pluck('user_id')->values(),
        ];
    }
}
