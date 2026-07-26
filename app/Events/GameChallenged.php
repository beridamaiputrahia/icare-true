<?php

namespace App\Events;

use App\Models\GameSession;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dikirim ke SATU user yang diundang (broadcastOn dipanggil sekali per
 * penerima dari controller — lihat GameSessionController::challenge()).
 */
class GameChallenged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public GameSession $session, public User $invitee) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('game-user.' . $this->invitee->id)];
    }

    public function broadcastAs(): string
    {
        return 'challenged';
    }

    public function broadcastWith(): array
    {
        return [
            'session_code' => $this->session->code,
            'game_type'    => $this->session->game_type,
            'host_name'    => $this->session->host->name,
            'host_id'      => $this->session->host_id,
        ];
    }
}
