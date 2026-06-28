<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

// Private conversation channel auth
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conv = Conversation::find($conversationId);
    if (!$conv) return false;

    if ($conv->type === 'global') return true;

    if ($conv->type === 'leader') return $user->isAdmin();

    // Private — must be a participant
    return $conv->participants()->where('user_id', $user->id)->exists();
});
