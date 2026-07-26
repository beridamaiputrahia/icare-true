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

// Game session channel — hanya peserta (baris di game_session_participants,
// termasuk host) yang boleh subscribe. Tidak perlu cek tenant_id: siapa pun
// yang tercatat sebagai peserta baris sesi itu SUDAH pasti pihak yang sah
// (baris peserta hanya bisa dibuat lewat GameSessionController::challenge,
// yang hanya mengizinkan user login mengundang user lain). Membandingkan
// tenant_id di sini dulu jadi bug: superadmin punya users.tenant_id = NULL
// by design (tenant aktifnya cuma ada di session 'active_tenant_id', lihat
// BelongsToTenant::resolveTenantId), sehingga NULL !== tenant_id sesi selalu
// gagal dan otorisasi channel ditolak terus untuk akun superadmin.
Broadcast::channel('game-session.{code}', function ($user, $code) {
    $session = GameSession::withoutTenantScope()->where('code', $code)->first();

    if (! $session) {
        \Illuminate\Support\Facades\Log::warning('[game-session channel] sesi tidak ditemukan', ['code' => $code, 'user_id' => $user->id]);
        return false;
    }

    $ok = $session->isParticipant((int) $user->id);
    if (! $ok) {
        \Illuminate\Support\Facades\Log::warning('[game-session channel] user bukan peserta sesi', [
            'code' => $code, 'user_id' => $user->id,
        ]);
    }
    return $ok;
});
