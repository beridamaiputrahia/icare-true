<?php

use App\Models\Conversation;
use App\Models\GameSession;
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

// Game user notification channel — hanya user sendiri yang bisa subscribe
Broadcast::channel('game-user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Game session channel — hanya challenger atau opponent dari tenant yang sama
Broadcast::channel('game-session.{code}', function ($user, $code) {
    $session = GameSession::withoutTenantScope()->where('code', $code)->first();
    if (! $session) return false;
    if ($session->tenant_id !== $user->tenant_id) return false;
    return $user->id === $session->challenger_id || $user->id === $session->opponent_id;
});
