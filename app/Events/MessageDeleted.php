<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $messageId, public int $conversationId, public string $conversationType)
    {
    }

    public function broadcastOn(): array
    {
        return match ($this->conversationType) {
            'global' => [new Channel("conversation.{$this->conversationId}")],
            default  => [new PrivateChannel("conversation.{$this->conversationId}")],
        };
    }

    public function broadcastWith(): array
    {
        return [
            'id'              => $this->messageId,
            'conversation_id' => $this->conversationId,
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.deleted';
    }
}
