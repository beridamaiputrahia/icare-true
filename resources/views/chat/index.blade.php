@extends('layouts.app')

@section('title', 'Live Chat')
@section('page-title', 'Live Chat Komunitas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Live Chat</li>
@endsection

@push('styles')
<style>
.chat-wrap { display: flex; height: calc(100vh - 180px); min-height: 500px; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.08); }

/* Sidebar */
.chat-sidebar { width: 260px; flex-shrink: 0; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; background: #f8fafc; }
.chat-sidebar-header { padding: .85rem 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: .875rem; }
.chat-list { overflow-y: auto; flex: 1; }
.chat-list-item {
    display: flex; align-items: center; gap: .65rem; padding: .65rem 1rem;
    cursor: pointer; transition: background .15s; border-bottom: 1px solid #f1f5f9;
    text-decoration: none; color: inherit;
}
.chat-list-item:hover, .chat-list-item.active { background: #e0e7ff; color: inherit; }
.chat-list-item.active { border-left: 3px solid var(--app-primary,#2563eb); }
.chat-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .85rem; font-weight: 700; color: #fff; flex-shrink: 0; overflow: hidden; }
.chat-avatar img { width: 100%; height: 100%; object-fit: cover; }
.chat-item-name { font-size: .82rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-item-last { font-size: .7rem; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.online-dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; flex-shrink: 0; }

/* Main */
.chat-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.chat-topbar { padding: .75rem 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: .75rem; background: #fff; }
.chat-messages { flex: 1; overflow-y: auto; padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: .65rem; scroll-behavior: smooth; }
.msg-row { display: flex; align-items: flex-end; gap: .5rem; }
.msg-row.mine { flex-direction: row-reverse; }
.msg-avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 700; color: #fff; flex-shrink: 0; overflow: hidden; }
.msg-avatar img { width: 100%; height: 100%; object-fit: cover; }
.msg-bubble { max-width: 68%; padding: .55rem .9rem; border-radius: 16px; font-size: .875rem; line-height: 1.5; position: relative; word-break: break-word; }
.msg-bubble.other { background: #f1f5f9; color: #1e293b; border-bottom-left-radius: 4px; }
.msg-bubble.mine { background: var(--app-primary,#2563eb); color: #fff; border-bottom-right-radius: 4px; }
.msg-meta { font-size: .65rem; margin-top: .25rem; opacity: .65; white-space: nowrap; }
.msg-name { font-size: .68rem; font-weight: 600; color: #64748b; margin-bottom: .15rem; }
.typing-indicator { display: flex; align-items: center; gap: .3rem; padding: .4rem .8rem; background: #f1f5f9; border-radius: 16px; font-size: .75rem; color: #64748b; }
.typing-dot { width: 6px; height: 6px; border-radius: 50%; background: #94a3b8; animation: bounce 1.4s infinite ease-in-out; }
.typing-dot:nth-child(2) { animation-delay: .2s; }
.typing-dot:nth-child(3) { animation-delay: .4s; }
@keyframes bounce { 0%,80%,100%{transform:scale(0)} 40%{transform:scale(1)} }
.chat-input-wrap { padding: .85rem 1.25rem; border-top: 1px solid #e2e8f0; background: #fff; }
.chat-input-row { display: flex; gap: .65rem; align-items: flex-end; }
#msgInput { flex: 1; resize: none; border-radius: 24px; padding: .65rem 1rem; font-size: .875rem; border: 1px solid #d1d5db; outline: none; max-height: 120px; overflow-y: auto; }
#msgInput:focus { border-color: var(--app-primary,#2563eb); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.send-btn { width: 44px; height: 44px; border-radius: 50%; background: var(--app-primary,#2563eb); color: #fff; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .2s; flex-shrink: 0; }
.send-btn:hover { background: var(--app-primary-dark, #1d4ed8); }
.send-btn:disabled { opacity: .5; cursor: not-allowed; }
.load-more-btn { text-align: center; }
.empty-chat { flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #94a3b8; }
.msg-delete-btn {
    border: none; background: none; color: #94a3b8; cursor: pointer;
    font-size: .7rem; padding: 0 .3rem; opacity: 0; transition: opacity .15s;
    align-self: center;
}
.msg-row:hover .msg-delete-btn { opacity: 1; }
.msg-delete-btn:hover { color: #dc3545; }

@media (max-width: 767px) {
    .chat-sidebar { display: none; }
    .chat-sidebar.show { display: flex; position: absolute; z-index: 100; top: 0; left: 0; bottom: 0; width: 240px; }
}
</style>
@endpush

@section('content')
<div class="chat-wrap">

    {{-- Sidebar ──────────────────────────────────────────────── --}}
    <div class="chat-sidebar" id="chatSidebar">
        <div class="chat-sidebar-header">
            <i class="fa-solid fa-comments text-primary me-2"></i>Percakapan
        </div>
        <div class="chat-list">
            {{-- Global --}}
            @if($global)
            <a href="{{ route('chat.index', ['conv' => $global->id]) }}"
               class="chat-list-item {{ $activeConv?->id === $global->id ? 'active' : '' }}">
                <div class="chat-avatar" style="background:#2563eb">
                    <i class="fa-solid fa-globe fa-sm"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="chat-item-name">Chat Komunitas</div>
                    <div class="chat-item-last">Semua Anggota</div>
                </div>
            </a>
            @endif

            {{-- Leader (admin only) --}}
            @if($leader && auth()->user()->isAdmin())
            <a href="{{ route('chat.index', ['conv' => $leader->id]) }}"
               class="chat-list-item {{ $activeConv?->id === $leader->id ? 'active' : '' }}">
                <div class="chat-avatar" style="background:#7c3aed">
                    <i class="fa-solid fa-crown fa-sm"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="chat-item-name">Chat Leader</div>
                    <div class="chat-item-last">Admin & Leader</div>
                </div>
            </a>
            @endif

            {{-- Private conversations --}}
            @if($privates->count())
            <div class="px-3 py-2" style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">
                Pesan Pribadi
            </div>
            @foreach($privates as $pConv)
            @php $other = $pConv->participants->firstWhere('id', '!=', auth()->id()); @endphp
            @if($other)
            <a href="{{ route('chat.index', ['conv' => $pConv->id]) }}"
               class="chat-list-item {{ $activeConv?->id === $pConv->id ? 'active' : '' }}">
                <div class="chat-avatar" style="background:var(--app-primary,#2563eb)">
                    @if($other->avatar_url)
                    <img src="{{ $other->avatar_url }}" alt="{{ $other->name }}">
                    @else
                    {{ strtoupper(substr($other->name,0,1)) }}
                    @endif
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="chat-item-name">{{ $other->name }}</div>
                    <div class="chat-item-last">{{ $other->is_online ? '● Online' : 'Offline' }}</div>
                </div>
                @if($pConv->unread_count)
                <span class="badge bg-danger rounded-pill" style="font-size:.6rem">{{ $pConv->unread_count }}</span>
                @endif
            </a>
            @endif
            @endforeach
            @endif

            {{-- Online Users (for starting private chat) --}}
            @if($onlineUsers->count())
            <div class="px-3 py-2" style="font-size:.65rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">
                Anggota Online
            </div>
            @foreach($onlineUsers as $ou)
            <a href="{{ route('chat.start-private', $ou) }}"
               class="chat-list-item">
                <div class="chat-avatar" style="background:#22c55e">
                    @if($ou->avatar_url)
                    <img src="{{ $ou->avatar_url }}" alt="{{ $ou->name }}">
                    @else
                    {{ strtoupper(substr($ou->name,0,1)) }}
                    @endif
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="chat-item-name">{{ $ou->name }}</div>
                    <div class="chat-item-last" style="color:#22c55e">● Online sekarang</div>
                </div>
                <i class="fa-solid fa-paper-plane fa-xs text-muted"></i>
            </a>
            @endforeach
            @endif
        </div>
    </div>

    {{-- Main Chat Area ───────────────────────────────────────── --}}
    <div class="chat-main">

        @if(!$activeConv)
        <div class="empty-chat">
            <i class="fa-solid fa-comments fa-3x mb-3 opacity-25"></i>
            <p class="fw-semibold">Pilih percakapan untuk mulai chat</p>
        </div>
        @else

        {{-- Chat topbar --}}
        <div class="chat-topbar">
            <div class="chat-avatar" style="background:var(--app-primary,#2563eb)">
                @if($activeConv->type === 'global')
                <i class="fa-solid fa-globe fa-sm"></i>
                @elseif($activeConv->type === 'leader')
                <i class="fa-solid fa-crown fa-sm"></i>
                @else
                @php $other = $activeConv->participants->firstWhere('id', '!=', auth()->id()); @endphp
                @if($other?->avatar_url)
                <img src="{{ $other->avatar_url }}" alt="{{ $other->name }}">
                @else
                {{ strtoupper(substr($other?->name ?? 'U',0,1)) }}
                @endif
                @endif
            </div>
            <div class="flex-grow-1">
                <div style="font-weight:600;font-size:.9rem">
                    @if($activeConv->type === 'global') Chat Komunitas
                    @elseif($activeConv->type === 'leader') Chat Leader
                    @else {{ $other?->name ?? 'Percakapan Pribadi' }}
                    @endif
                </div>
                <div style="font-size:.7rem;color:#94a3b8" id="convStatus">
                    @if($activeConv->type === 'global') Semua Anggota
                    @elseif($activeConv->type === 'leader') Admin & Leader
                    @else {{ $other?->is_online ? '● Online' : 'Offline' }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" id="msgContainer">
            {{-- Load more button --}}
            @if($messages->count() >= 50)
            <div class="load-more-btn mb-2">
                <button class="btn btn-outline-secondary btn-sm" id="loadMoreBtn"
                        data-conv="{{ $activeConv->id }}"
                        data-before="{{ $messages->first()?->id ?? 0 }}">
                    <i class="fa-solid fa-arrow-up me-1"></i>Pesan Lama
                </button>
            </div>
            @endif

            @forelse($messages as $msg)
            @php $isMe = $msg->user_id === auth()->id(); @endphp
            @php $canDelete = !$msg->is_deleted && auth()->user()->can('delete', $msg); @endphp
            <div class="msg-row {{ $isMe ? 'mine' : '' }}" id="msg-{{ $msg->id }}">
                @if(!$isMe)
                <div class="msg-avatar" style="background:var(--app-primary,#2563eb)">
                    @if($msg->user?->avatar_url)
                    <img src="{{ $msg->user->avatar_url }}" alt="{{ $msg->user->name }}">
                    @else
                    {{ strtoupper(substr($msg->user?->name ?? 'U',0,1)) }}
                    @endif
                </div>
                @endif
                @if($canDelete)
                <button type="button" class="msg-delete-btn" data-msg-id="{{ $msg->id }}" title="Hapus pesan">
                    <i class="fa-solid fa-trash"></i>
                </button>
                @endif
                <div>
                    @if(!$isMe && $activeConv->type !== 'private')
                    <div class="msg-name">{{ $msg->user?->name }}</div>
                    @endif
                    <div class="msg-bubble {{ $isMe ? 'mine' : 'other' }}">
                        {{ $msg->display_body }}
                    </div>
                    <div class="msg-meta {{ $isMe ? 'text-end' : '' }}">
                        {{ $msg->created_at->diffForHumans() }}
                        @if($isMe)
                        <span class="ms-1"><i class="fa-solid fa-check-double"></i></span>
                        @endif
                    </div>
                </div>
                @if($isMe)
                <div class="chat-avatar" style="background:var(--app-primary,#2563eb);width:30px;height:30px;font-size:.72rem">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
                @endif
            </div>
            @empty
            <div class="empty-chat">
                <i class="fa-solid fa-comment-dots fa-2x mb-2 opacity-25"></i>
                <p class="small">Belum ada pesan. Mulai percakapan!</p>
            </div>
            @endforelse

            {{-- Typing indicator --}}
            <div id="typingIndicator" style="display:none" class="msg-row">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <span id="typingName" style="margin-left:.2rem"></span>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="chat-input-wrap">
            <div class="chat-input-row">
                <textarea id="msgInput" placeholder="Ketik pesan..." rows="1"
                          maxlength="2000"></textarea>
                <button class="send-btn" id="sendBtn" title="Kirim">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>

        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
@if($activeConv)
const CONV_ID   = {{ $activeConv->id }};
const CONV_TYPE = @json($activeConv->type);
const MY_ID     = {{ auth()->id() }};
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const container = document.getElementById('msgContainer');
const input     = document.getElementById('msgInput');
const sendBtn   = document.getElementById('sendBtn');

// ── Scroll to bottom on load ──────────────────────────────────
container.scrollTop = container.scrollHeight;

// ── Send message ──────────────────────────────────────────────
async function sendMessage() {
    const body = input.value.trim();
    if (!body) return;
    sendBtn.disabled = true;

    try {
        const r = await fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ conversation_id: CONV_ID, body })
        });
        if (r.ok) {
            const data = await r.json();
            appendMessage(data.message, true);
            input.value = '';
            input.style.height = 'auto';
            container.scrollTop = container.scrollHeight;
        }
    } catch (e) { console.error(e); }
    finally { sendBtn.disabled = false; input.focus(); }
}

sendBtn.addEventListener('click', sendMessage);
input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});

// Auto-resize textarea
input.addEventListener('input', () => {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    sendTyping(input.value.length > 0);
});

// ── Append message to DOM ─────────────────────────────────────
function appendMessage(msg, isMine) {
    const indicator = document.getElementById('typingIndicator');
    const row = document.createElement('div');
    row.className = 'msg-row' + (isMine ? ' mine' : '');
    row.id = 'msg-' + msg.id;

    const avatarHtml = `<div class="msg-avatar" style="background:var(--app-primary,#2563eb);width:30px;height:30px;font-size:.72rem">
        ${(msg.user_name || '?')[0].toUpperCase()}
    </div>`;
    const deleteBtnHtml = isMine ? `<button type="button" class="msg-delete-btn" data-msg-id="${msg.id}" title="Hapus pesan"><i class="fa-solid fa-trash"></i></button>` : '';

    row.innerHTML = (!isMine ? avatarHtml : '') + deleteBtnHtml + `
        <div>
            <div class="msg-bubble ${isMine ? 'mine' : 'other'}">${escHtml(msg.body)}</div>
            <div class="msg-meta ${isMine ? 'text-end' : ''}">${msg.created_at_human}
                ${isMine ? '<span class="ms-1"><i class="fa-solid fa-check-double"></i></span>' : ''}
            </div>
        </div>
    ` + (isMine ? avatarHtml : '');

    container.insertBefore(row, indicator);
    container.scrollTop = container.scrollHeight;
}

// ── Delete message ─────────────────────────────────────────────
container.addEventListener('click', async e => {
    const btn = e.target.closest('.msg-delete-btn');
    if (!btn) return;
    if (!confirm('Hapus pesan ini?')) return;

    const msgId = btn.dataset.msgId;
    try {
        const r = await fetch(`{{ url('/chat/messages') }}/${msgId}/delete`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        if (r.ok) {
            const bubble = document.getElementById('msg-' + msgId)?.querySelector('.msg-bubble');
            if (bubble) bubble.textContent = '[Pesan telah dihapus]';
            btn.remove();
        }
    } catch (e) { console.error(e); }
});

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Load more ─────────────────────────────────────────────────
document.getElementById('loadMoreBtn')?.addEventListener('click', async function() {
    const beforeId = this.dataset.before;
    const r = await fetch(`{{ route('chat.load-more') }}?conversation_id=${CONV_ID}&before_id=${beforeId}`, {
        headers: { 'Accept': 'application/json' }
    });
    if (r.ok) {
        const data = await r.json();
        const firstMsg = container.querySelector('.msg-row');
        data.messages.forEach(msg => {
            const isMine = msg.user_id === MY_ID;
            const row = document.createElement('div');
            row.className = 'msg-row' + (isMine ? ' mine' : '');
            row.id = 'msg-' + msg.id;
            const avatarHtml = `<div class="msg-avatar" style="background:var(--app-primary,#2563eb);width:30px;height:30px;font-size:.72rem">${msg.user_name[0].toUpperCase()}</div>`;
            row.innerHTML = (!isMine ? avatarHtml : '') + `
                <div>
                    <div class="msg-bubble ${isMine ? 'mine' : 'other'}">${escHtml(msg.body)}</div>
                    <div class="msg-meta ${isMine ? 'text-end' : ''}">${msg.created_at_human}</div>
                </div>
            ` + (isMine ? avatarHtml : '');
            container.insertBefore(row, firstMsg);
        });
        if (data.messages.length > 0) this.dataset.before = data.messages[0].id;
        if (!data.has_more) this.remove();
    }
});

// ── Pusher real-time ───────────────────────────────────────────
const PUSHER_KEY     = '{{ config('broadcasting.connections.pusher.key') }}';
const PUSHER_CLUSTER = '{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}';

if (PUSHER_KEY) {
    try {
        const pusher = new Pusher(PUSHER_KEY, {
            cluster: PUSHER_CLUSTER,
            authEndpoint: '/broadcasting/auth',
            auth: { headers: { 'X-CSRF-TOKEN': CSRF } },
        });

        // Channel "global" bersifat publik; leader/private butuh otorisasi peserta.
        const channelName = CONV_TYPE === 'global' ? 'conversation.' + CONV_ID : 'private-conversation.' + CONV_ID;
        const channel = pusher.subscribe(channelName);

        channel.bind('message.sent', data => {
            if (data.user_id === MY_ID) return;
            appendMessage({
                id: data.id, user_id: data.user_id, user_name: data.user_name,
                body: data.body, created_at_human: data.created_at_human
            }, false);
        });

        channel.bind('user.typing', data => {
            if (data.user_id === MY_ID) return;
            const ti = document.getElementById('typingIndicator');
            const tn = document.getElementById('typingName');
            if (data.is_typing) {
                tn.textContent = data.user_name + ' sedang mengetik...';
                ti.style.display = 'flex';
                container.scrollTop = container.scrollHeight;
            } else {
                ti.style.display = 'none';
            }
        });
    } catch (e) { console.warn('Pusher not available:', e.message); }
}

// ── Typing throttle ───────────────────────────────────────────
let typingTimer;
let lastTyping = false;
function sendTyping(isTyping) {
    if (isTyping === lastTyping) return;
    lastTyping = isTyping;
    fetch('{{ route('chat.typing') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ conversation_id: CONV_ID, is_typing: isTyping })
    }).catch(() => {});
    if (isTyping) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => sendTyping(false), 3000);
    }
}
@endif
</script>
@endpush
