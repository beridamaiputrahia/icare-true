<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        $this->message->loadMissing('user', 'conversation');
    }

    public function broadcastOn(): array
    {
        $convId = $this->message->conversation_id;

        return match($this->message->conversation->type ?? 'private') {
            'global' => [new Channel("conversation.{$convId}")],
            'leader' => [new PrivateChannel("conversation.{$convId}")],
            default  => [new PrivateChannel("conversation.{$convId}")],
        };
    }

    public function broadcastWith(): array
    {
        $user = $this->message->user;

        return [
            'id'              => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'user_id'         => $user->id,
            'user_name'       => $user->name,
            'user_avatar_url' => $user->avatar_url,
            'user_level'      => $user->level,
            'body'            => $this->message->body,
            'created_at'      => $this->message->created_at->toISOString(),
            'created_at_human'=> $this->message->created_at->diffForHumans(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
