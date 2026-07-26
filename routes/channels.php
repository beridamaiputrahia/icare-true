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

    if (! $session) {
        \Illuminate\Support\Facades\Log::warning('[game-session channel] sesi tidak ditemukan', ['code' => $code, 'user_id' => $user->id]);
        return false;
    }

    if ((int) $session->tenant_id !== (int) $user->tenant_id) {
        \Illuminate\Support\Facades\Log::warning('[game-session channel] tenant_id tidak cocok', [
            'code' => $code, 'user_id' => $user->id,
            'user_tenant_id' => $user->tenant_id, 'session_tenant_id' => $session->tenant_id,
        ]);
        return false;
    }

    $ok = (int) $user->id === (int) $session->challenger_id || (int) $user->id === (int) $session->opponent_id;
    if (! $ok) {
        \Illuminate\Support\Facades\Log::warning('[game-session channel] user bukan peserta sesi', [
            'code' => $code, 'user_id' => $user->id,
            'challenger_id' => $session->challenger_id, 'opponent_id' => $session->opponent_id,
        ]);
    }
    return $ok;
});
