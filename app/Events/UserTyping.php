<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public User $user,
        public int  $conversationId,
        public bool $isTyping
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel("conversation.{$this->conversationId}")];
    }

    public function broadcastWith(): array
    {
        return [
            'user_id'   => $this->user->id,
            'user_name' => $this->user->name,
            'is_typing' => $this->isTyping,
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.typing';
    }
}
