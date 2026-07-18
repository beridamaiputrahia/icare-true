{{--
    Center Focus Navigation Carousel
    I Care True — Bottom Navigation, All Screen Sizes
    Replaces sidebar entirely. Shows 3 items at a time with 3D focus effect.
--}}

{{-- ══════════════════════════════════════════════════════════════════
     STYLES — rendered inline (not via @push) so they always appear
     regardless of when @stack('styles') was called in the layout.
     ══════════════════════════════════════════════════════════════════ --}}
@once
<style>
/* ═══════════════════════════════════════════════════════════════
   DESIGN TOKENS
   ═══════════════════════════════════════════════════════════════ */
:root {
    /* Glass surface */
    --nc-glass:         rgba(255, 255, 255, 0.88);
    --nc-glass-border:  rgba(255, 255, 255, 0.65);
    --nc-blur:          saturate(160%) blur(20px);
    --nc-shadow:        0 -4px 40px rgba(0,0,0,.10), 0 -1px 0 rgba(255,255,255,.6) inset;

    /* Colors */
    --nc-active:        var(--app-primary, #2563eb);
    --nc-active-bg:     rgba(37, 99, 235, 0.13);
    --nc-active-glow:   rgba(37, 99, 235, 0.30);
    --nc-idle-icon:     rgba(100, 116, 139, 0.55);
    --nc-idle-label:    rgba(71, 85, 105, 0.60);
    --nc-badge:         #ef4444;
    --nc-dot-idle:      rgba(148, 163, 184, 0.40);
    --nc-dot-active:    var(--app-primary, #2563eb);

    /* Sizing */
    --nc-height:        110px;
    --nc-carousel-h:    68px;
    --nc-icon-sz:       48px;
    --nc-icon-r:        14px;
    --nc-item-w:        72px;
    --nc-offset:        86px;

    /* Transition */
    --nc-ease:          cubic-bezier(0.34, 1.56, 0.64, 1);
    --nc-ease-out:      cubic-bezier(0.4, 0, 0.2, 1);
}

/* Dark mode overrides */
@media (prefers-color-scheme: dark) {
    :root {
        --nc-glass:         rgba(15, 23, 42, 0.92);
        --nc-glass-border:  rgba(255, 255, 255, 0.07);
        --nc-shadow:        0 -4px 40px rgba(0,0,0,.45), 0 -1px 0 rgba(255,255,255,.05) inset;
        --nc-idle-icon:     rgba(148, 163, 184, 0.45);
        --nc-idle-label:    rgba(100, 116, 139, 0.60);
        --nc-active-bg:     rgba(37, 99, 235, 0.22);
        --nc-dot-idle:      rgba(51, 65, 85, 0.60);
    }
}

/* ═══════════════════════════════════════════════════════════════
   NAV SHELL — fixed bottom, full width
   ═══════════════════════════════════════════════════════════════ */
#nav-carousel {
    position:         fixed !important;
    bottom:           0 !important;
    left:             0 !important;
    right:            0 !important;
    z-index:          1050;
    background:       var(--nc-glass);
    backdrop-filter:  var(--nc-blur);
    -webkit-backdrop-filter: var(--nc-blur);
    border-top:       1.5px solid var(--nc-glass-border);
    border-radius:    24px 24px 0 0;
    box-shadow:       var(--nc-shadow);
    user-select:      none;
    padding-bottom:   env(safe-area-inset-bottom, 0px);
    /* Guard against any inherited margin/padding */
    margin:           0 !important;
    padding-left:     0;
    padding-right:    0;
    padding-top:      0;
    width:            100% !important;
}

/* ═══════════════════════════════════════════════════════════════
   TRACK OUTER — clipping wrapper with fade edges
   ═══════════════════════════════════════════════════════════════ */
#nav-track-outer {
    position:   relative;
    height:     var(--nc-carousel-h);
    overflow:   hidden;
}

/* Fade edge hints */
.nav-fade-left,
.nav-fade-right {
    position:       absolute;
    top:            0;
    bottom:         0;
    width:          48px;
    z-index:        5;
    pointer-events: none;
}
.nav-fade-left  {
    left:       0;
    background: linear-gradient(to right, var(--nc-glass), transparent);
}
.nav-fade-right {
    right:      0;
    background: linear-gradient(to left,  var(--nc-glass), transparent);
}

/* ═══════════════════════════════════════════════════════════════
   INNER TRACK — perspective context
   ═══════════════════════════════════════════════════════════════ */
#nav-track {
    position:            absolute;
    inset:               0;
    display:             flex;
    align-items:         center;
    justify-content:     center;
    perspective:         600px;
    perspective-origin:  50% 50%;
    /* Remove any inherited list/flex behaviour */
    flex-direction:      row;
    flex-wrap:           nowrap;
}

/* ═══════════════════════════════════════════════════════════════
   ITEM CARDS — base styles
   ═══════════════════════════════════════════════════════════════ */
.nav-item-card {
    position:        absolute !important;
    left:            50%;
    top:             50%;
    /* Base transform: items positioned from track center */
    transform:       translateX(-50%) translateY(-50%);

    display:         flex;
    flex-direction:  column;
    align-items:     center;
    gap:             5px;
    text-decoration: none;
    cursor:          pointer;
    outline:         none;
    width:           var(--nc-item-w);

    transition:
        transform  .40s var(--nc-ease),
        opacity    .32s ease,
        filter     .32s ease;
}

/* Remove Bootstrap link underlines etc. */
.nav-item-card:hover,
.nav-item-card:focus {
    text-decoration: none;
    outline:         none;
}

/* ═══════════════════════════════════════════════════════════════
   ICON WRAPPER
   ═══════════════════════════════════════════════════════════════ */
.nic-icon-wrap {
    position:        relative;
    width:           var(--nc-icon-sz);
    height:          var(--nc-icon-sz);
    flex-shrink:     0;
    display:         flex;
    align-items:     center;
    justify-content: center;
}

.nic-icon {
    width:           var(--nc-icon-sz);
    height:          var(--nc-icon-sz);
    display:         flex;
    align-items:     center;
    justify-content: center;
    font-size:       1.25rem;
    color:           var(--nc-idle-icon);
    background:      transparent;
    position:        relative;
    z-index:         1;
    transition:
        color      .28s ease,
        font-size  .28s var(--nc-ease),
        transform  .28s var(--nc-ease);
}

/* ═══════════════════════════════════════════════════════════════
   GLOW AURA (center item only)
   ═══════════════════════════════════════════════════════════════ */
.nic-glow {
    position:       absolute;
    inset:          -18px;
    border-radius:  50%;
    background:     radial-gradient(circle at center,
                        var(--nc-active-glow) 0%,
                        rgba(37,99,235,.10) 45%,
                        transparent 72%);
    opacity:        0;
    pointer-events: none;
    z-index:        0;
    transition:     opacity .40s ease;
}

/* ═══════════════════════════════════════════════════════════════
   BADGE
   ═══════════════════════════════════════════════════════════════ */
.nic-badge {
    position:        absolute;
    top:             -3px;
    right:           -3px;
    min-width:       18px;
    height:          18px;
    padding:         0 4px;
    border-radius:   9px;
    background:      var(--nc-badge);
    color:           #fff;
    font-size:       .58rem;
    font-weight:     700;
    display:         flex;
    align-items:     center;
    justify-content: center;
    border:          2px solid rgba(255,255,255,.95);
    z-index:         2;
    line-height:     1;
}

/* ═══════════════════════════════════════════════════════════════
   LABEL (below icon, visible on active center only)
   ═══════════════════════════════════════════════════════════════ */
.nic-label {
    font-size:      .65rem;
    font-weight:    600;
    letter-spacing: .02em;
    white-space:    nowrap;
    color:          var(--nc-idle-label);
    opacity:        0;
    line-height:    1;
    transition:     color .28s ease, opacity .28s ease;
    max-width:      80px;
    overflow:       hidden;
    text-overflow:  ellipsis;
}

/* Active bar indicator */
.nic-active-bar {
    width:          20px;
    height:         3px;
    border-radius:  2px;
    background:     var(--nc-active);
    opacity:        0;
    transform:      scaleX(0);
    transition:     opacity .28s ease, transform .35s var(--nc-ease);
    margin-top:     -2px;
}

/* ═══════════════════════════════════════════════════════════════
   COVER FLOW POSITIONS
   All items anchored at left:50%; top:50% in the track,
   then displaced by pixel offsets from center using --nc-offset.
   ═══════════════════════════════════════════════════════════════ */
.nav-item-card[data-pos="-2"] {
    transform:      translateX(calc(-50% - var(--nc-offset) * 1.85)) translateY(-50%)
                    rotateY(52deg) scale(0.35);
    opacity:        0;
    pointer-events: none;
}

.nav-item-card[data-pos="-1"] {
    transform:      translateX(calc(-50% - var(--nc-offset))) translateY(-50%)
                    rotateY(34deg) scale(0.68);
    opacity:        0.55;
    filter:         brightness(0.58) saturate(0.35);
    pointer-events: auto;
}

.nav-item-card[data-pos="0"] {
    transform:      translateX(-50%) translateY(-50%)
                    rotateY(0deg) scale(1.12);
    opacity:        1;
    filter:         none;
    z-index:        10;
    pointer-events: auto;
}

.nav-item-card[data-pos="1"] {
    transform:      translateX(calc(-50% + var(--nc-offset))) translateY(-50%)
                    rotateY(-34deg) scale(0.68);
    opacity:        0.55;
    filter:         brightness(0.58) saturate(0.35);
    pointer-events: auto;
}

.nav-item-card[data-pos="2"] {
    transform:      translateX(calc(-50% + var(--nc-offset) * 1.85)) translateY(-50%)
                    rotateY(-52deg) scale(0.35);
    opacity:        0;
    pointer-events: none;
}

/* ── ACTIVE CENTER STYLES ───────────────────────────────────── */
.nav-item-card[data-pos="0"] .nic-icon {
    color:      var(--nc-active);
    background: transparent;          /* no box — icon menyatu dengan latar */
    font-size:  1.55rem;              /* sedikit lebih besar saat aktif */
}
.nav-item-card[data-pos="0"] .nic-glow       { opacity: 1; }
.nav-item-card[data-pos="0"] .nic-label      { color: var(--nc-active); opacity: 1; }
.nav-item-card[data-pos="0"] .nic-active-bar { opacity: 1; transform: scaleX(1); }
.nav-item-card[data-pos="0"] .nic-badge      { transform: scale(1.15); }

/* ═══════════════════════════════════════════════════════════════
   HAPTIC BOUNCE (keyframes match center item's exact transform)
   ═══════════════════════════════════════════════════════════════ */
@keyframes nic-bounce {
    0%   { transform: translateX(-50%) translateY(-50%) rotateY(0deg) scale(1.12); }
    28%  { transform: translateX(calc(-50% - 5px)) translateY(-50%) rotateY(0deg) scale(1.18); }
    60%  { transform: translateX(calc(-50% + 5px)) translateY(-50%) rotateY(0deg) scale(1.18); }
    100% { transform: translateX(-50%) translateY(-50%) rotateY(0deg) scale(1.12); }
}
.nav-item-card[data-pos="0"].is-bouncing {
    animation: nic-bounce .30s cubic-bezier(.36,.07,.19,.97);
}

/* Speed up transitions while dragging */
#nav-carousel.is-dragging .nav-item-card {
    transition:
        transform .05s linear,
        opacity   .05s linear,
        filter    .05s linear;
}

/* ═══════════════════════════════════════════════════════════════
   DOTS INDICATOR
   ═══════════════════════════════════════════════════════════════ */
.nav-dots-wrap {
    display:          flex;
    justify-content:  center;
    overflow:         hidden;
    padding:          5px 0 4px;
}

#nav-dots-track {
    display:     flex;
    gap:         4px;
    align-items: center;
    transition:  transform .35s var(--nc-ease-out);
}

.nav-dot {
    width:        5px;
    height:       5px;
    border-radius: 50%;
    background:   var(--nc-dot-idle);
    flex-shrink:  0;
    cursor:       pointer;
    transition:
        width      .35s var(--nc-ease-out),
        background .28s ease;
}
.nav-dot.active {
    width:        22px;
    border-radius: 3px;
    background:   var(--nc-dot-active);
}

/* ═══════════════════════════════════════════════════════════════
   USER STRIP (name + logout at bottom of nav)
   ═══════════════════════════════════════════════════════════════ */
.nav-user-strip {
    display:     flex;
    align-items: center;
    gap:         .6rem;
    padding:     4px 1.25rem 8px;
    border-top:  1px solid rgba(0,0,0,.06);
}
.nus-avatar {
    width:           32px;
    height:          32px;
    border-radius:   50%;
    background:      var(--nc-active);
    color:           #fff;
    font-size:       .72rem;
    font-weight:     700;
    display:         flex;
    align-items:     center;
    justify-content: center;
    flex-shrink:     0;
}
.nus-avatar-img {
    width:        32px;
    height:       32px;
    border-radius: 50%;
    object-fit:   cover;
    flex-shrink:  0;
    border:       2px solid rgba(255,255,255,.80);
    display:      block;
}
.nus-info     { flex: 1; min-width: 0; }
.nus-name     { font-size: .72rem; font-weight: 600; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.nus-role     { font-size: .62rem; color: #94a3b8; text-transform: capitalize; }
.nus-logout   {
    background:   none;
    border:       none;
    padding:      5px 8px;
    cursor:       pointer;
    color:        #94a3b8;
    border-radius: 8px;
    transition:   background .2s, color .2s;
    font-size:    .9rem;
    line-height:  1;
}
.nus-logout:hover { background: #fee2e2; color: #dc2626; }

/* ═══════════════════════════════════════════════════════════════
   DESKTOP — floating pill centered at bottom
   ═══════════════════════════════════════════════════════════════ */
@media (min-width: 768px) {
    :root { --nc-offset: 100px; }

    #nav-carousel {
        left:          50% !important;
        right:         auto !important;
        transform:     translateX(-50%) !important;
        width:         min(600px, 100vw) !important;
        border-radius: 20px 20px 0 0;
        box-shadow:
            0 -8px 48px rgba(0,0,0,.14),
            0  0  0  1px rgba(255,255,255,.55) inset;
    }
}

@media (min-width: 1024px) {
    :root {
        --nc-offset:  112px;
        --nc-icon-sz: 52px;
    }
    #nav-carousel { width: min(660px, 100vw) !important; }
}

/* ═══════════════════════════════════════════════════════════════
   iOS SAFE AREA
   ═══════════════════════════════════════════════════════════════ */
@supports (padding-bottom: env(safe-area-inset-bottom)) {
    #nav-carousel {
        padding-bottom: env(safe-area-inset-bottom);
    }
}
</style>
@endonce

{{-- ══════════════════════════════════════════════════════════════
     PHP: menu definition, active detection, position pre-render
     ══════════════════════════════════════════════════════════════ --}}
@php
use Illuminate\Support\Facades\Route as RouteFacade;

$__authUser = auth()->user();
$isAdmin    = $__authUser?->isAdmin();
$isLeader   = $__authUser?->isLeader(); // admin + ICL + CTL
$notif      = $_notifCount ?? 0;

$allItems = [
    ['icon' => 'fa-gauge-high',       'label' => 'Dashboard',   'route' => 'dashboard',           'pattern' => 'dashboard'],
    ['icon' => 'fa-calendar-days',    'label' => 'Jadwal',      'route' => 'schedules.index',     'pattern' => 'schedules.*'],
    ['icon' => 'fa-bullhorn',         'label' => 'Pengumuman',  'route' => 'announcements.index', 'pattern' => 'announcements.*', 'badge' => $notif],
    ['icon' => 'fa-book-open-reader', 'label' => 'Renungan',    'route' => 'devotions.index',     'pattern' => 'devotions.*'],
    ['icon' => 'fa-users',            'label' => 'Anggota',     'route' => 'members.index',       'pattern' => 'members.*'],
    ['icon' => 'fa-hands-praying',    'label' => 'Doa',         'route' => 'prayers.index',       'pattern' => 'prayers.*'],
    ['icon' => 'fa-trophy',           'label' => 'Achievement', 'route' => 'achievements.index',  'pattern' => 'achievements.*'],
    ['icon' => 'fa-ranking-star',     'label' => 'Leaderboard', 'route' => 'leaderboard.index',   'pattern' => 'leaderboard.*'],
    ['icon' => 'fa-gamepad',          'label' => 'Game',        'route' => 'game.index',          'pattern' => 'game.*'],
    ['icon' => 'fa-coins',            'label' => 'Poin',        'route' => 'points.index',        'pattern' => 'points.*'],
    ['icon' => 'fa-images',           'label' => 'Galeri',      'route' => 'albums.index',        'pattern' => 'albums.*'],
    ['icon' => 'fa-comments',         'label' => 'Chat',        'route' => 'chat.index',          'pattern' => 'chat.*'],
    ['icon' => 'fa-chart-line',       'label' => 'Statistik',   'route' => 'statistics.index',    'pattern' => 'statistics.*'],
    ['icon' => 'fa-user-gear',        'label' => 'Profil',      'route' => 'profile.edit',        'pattern' => 'profile.*'],
    $isLeader ? ['icon' => 'fa-chart-pie', 'label' => 'Analytics',    'route' => 'analytics.index',    'pattern' => 'analytics.*']    : null,
    $isAdmin  ? ['icon' => 'fa-bible',     'label' => 'Ayat Harian',  'route' => 'daily-verses.index', 'pattern' => 'daily-verses.*'] : null,
    $isAdmin  ? ['icon' => 'fa-sliders',   'label' => 'Pengaturan',   'route' => 'settings.index',     'pattern' => 'settings.*']     : null,
];

$navItems = array_values(array_filter($allItems));
$total    = count($navItems);

$activeIdx = 0;
foreach ($navItems as $i => $item) {
    if (request()->routeIs($item['pattern'])) {
        $activeIdx = $i;
        break;
    }
}
@endphp

{{-- ══════════════════════════════════════════════════════════════
     HTML: Navigation Carousel
     ══════════════════════════════════════════════════════════════ --}}
<nav id="nav-carousel" role="navigation" aria-label="Navigation Carousel">

    {{-- ── TRACK: holds all menu item cards ────────────────────── --}}
    <div id="nav-track-outer">
        <div id="nav-track" role="list">
            @foreach($navItems as $i => $item)
            @php
                try { $url = route($item['route']); } catch (\Exception $e) { $url = '#'; }
                $badge   = $item['badge'] ?? 0;
                $initPos = max(-2, min(2, $i - $activeIdx));
            @endphp
            <a href="{{ $url }}"
               class="nav-item-card"
               data-index="{{ $i }}"
               data-pos="{{ $initPos }}"
               data-label="{{ $item['label'] }}"
               aria-label="{{ $item['label'] }}{{ $badge > 0 ? ' ('.$badge.' notif)' : '' }}"
               role="listitem"
               draggable="false">

                <div class="nic-icon-wrap">
                    <div class="nic-icon">
                        <i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i>
                    </div>
                    @if($badge > 0)
                    <span class="nic-badge" aria-hidden="true">{{ $badge > 9 ? '9+' : $badge }}</span>
                    @endif
                    <div class="nic-glow" aria-hidden="true"></div>
                </div>

                <span class="nic-label">{{ $item['label'] }}</span>
                <div class="nic-active-bar" aria-hidden="true"></div>

            </a>
            @endforeach
        </div>

        <div class="nav-fade-left"  aria-hidden="true"></div>
        <div class="nav-fade-right" aria-hidden="true"></div>
    </div>

    {{-- ── DOTS INDICATOR ─────────────────────────────────────── --}}
    <div class="nav-dots-wrap" aria-hidden="true">
        <div id="nav-dots-track">
            @foreach($navItems as $i => $item)
            <div class="nav-dot{{ $i === $activeIdx ? ' active' : '' }}"
                 data-dot="{{ $i }}"></div>
            @endforeach
        </div>
    </div>

    {{-- ── USER STRIP ─────────────────────────────────────────── --}}
    @php
        $__u    = auth()->user();
        $__foto = $__u->avatar
            ? \Illuminate\Support\Facades\Storage::url($__u->avatar)
            : ($__u->member?->foto
                ? \Illuminate\Support\Facades\Storage::url($__u->member->foto)
                : null);
    @endphp
    <div class="nav-user-strip" id="nav-user-strip">
        @if($__foto)
            <img src="{{ $__foto }}" class="nus-avatar-img"
                 alt="{{ $__u->name }}"
                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'nus-avatar',textContent:'{{ strtoupper(substr($__u->name,0,1)) }}'}))"
            >
        @else
            <div class="nus-avatar">{{ strtoupper(substr($__u->name, 0, 1)) }}</div>
        @endif
        <div class="nus-info">
            <div class="nus-name">{{ Str::limit($__u->name, 20) }}</div>
            <div class="nus-role">{{ $__u->roleLabel() }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="ms-auto">
            @csrf
            <button type="submit" class="nus-logout" title="Keluar">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>
    </div>

</nav>

{{-- ══════════════════════════════════════════════════════════════
     SCRIPTS — pushed to @stack('scripts') which is AFTER this
     component in app.blade.php, so the push is captured correctly.
     ══════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    'use strict';

    const NAV       = document.getElementById('nav-carousel');
    const ITEMS     = Array.from(document.querySelectorAll('.nav-item-card'));
    const DOTS      = Array.from(document.querySelectorAll('.nav-dot'));
    const DOT_TRACK = document.getElementById('nav-dots-track');
    const TOTAL     = ITEMS.length;

    if (!NAV || TOTAL === 0) return;

    let active = {{ $activeIdx }};

    /* ── Render positions ────────────────────────────────────── */
    function render() {
        ITEMS.forEach((el, i) => {
            const pos = Math.max(-2, Math.min(2, i - active));
            el.setAttribute('data-pos', pos);
        });
        syncDots();
    }

    /* ── Sync indicator dots ─────────────────────────────────── */
    function syncDots() {
        const DOT_W    = 5 + 4;
        const ACTIVE_W = 22 + 4;
        const WRAP_W   = DOT_TRACK.parentElement.offsetWidth;

        DOTS.forEach((d, i) => d.classList.toggle('active', i === active));

        let pos = 0;
        for (let i = 0; i < active; i++) pos += DOT_W;
        pos += ACTIVE_W / 2;
        const offset = Math.max(0, pos - WRAP_W / 2);
        DOT_TRACK.style.transform = `translateX(${-offset}px)`;
    }

    /* ── Navigate to index ───────────────────────────────────── */
    function goTo(idx, navigate) {
        const prev = active;
        active = Math.max(0, Math.min(TOTAL - 1, idx));
        render();

        const center = ITEMS[active];
        center.classList.remove('is-bouncing');
        void center.offsetWidth;
        center.classList.add('is-bouncing');
        center.addEventListener('animationend',
            () => center.classList.remove('is-bouncing'),
            { once: true }
        );

        if (navigator.vibrate && active !== prev) navigator.vibrate(8);

        if (navigate) {
            const href = center.getAttribute('href');
            if (href && href !== '#') window.location.href = href;
        }
    }

    /* ── Swipe / Drag ────────────────────────────────────────── */
    let startX = 0, startY = 0, dragging = false, detectScroll = null;
    const THRESHOLD = 44;

    function swipeStart(x, y) {
        startX = x; startY = y;
        dragging = true; detectScroll = null;
        NAV.classList.add('is-dragging');
    }
    function swipeMove(x, y) {
        if (!dragging) return;
        if (detectScroll === null) {
            detectScroll = Math.abs(y - startY) > Math.abs(x - startX);
        }
        if (detectScroll) {
            dragging = false;
            NAV.classList.remove('is-dragging');
        }
    }
    function swipeEnd(x) {
        NAV.classList.remove('is-dragging');
        if (!dragging) return;
        dragging = false;
        const dx = x - startX;
        if (Math.abs(dx) >= THRESHOLD) goTo(active + (dx < 0 ? 1 : -1));
    }

    NAV.addEventListener('touchstart', e => swipeStart(e.touches[0].clientX, e.touches[0].clientY), { passive: true });
    NAV.addEventListener('touchmove',  e => swipeMove(e.touches[0].clientX, e.touches[0].clientY),  { passive: true });
    NAV.addEventListener('touchend',   e => swipeEnd(e.changedTouches[0].clientX),                   { passive: true });

    NAV.addEventListener('mousedown', e => swipeStart(e.clientX, e.clientY));
    window.addEventListener('mousemove', e => { if (dragging) swipeMove(e.clientX, e.clientY); });
    window.addEventListener('mouseup',   e => { if (dragging) swipeEnd(e.clientX); });

    /* ── Click behavior ──────────────────────────────────────── */
    ITEMS.forEach((el, i) => {
        el.addEventListener('click', function (e) {
            const pos = parseInt(this.getAttribute('data-pos') || '0', 10);
            if (pos === 0) return; /* centered → follow href */
            e.preventDefault();
            goTo(i);
        });
    });

    /* ── Keyboard ────────────────────────────────────────────── */
    document.addEventListener('keydown', e => {
        const tag = document.activeElement?.tagName;
        if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') return;
        if (e.key === 'ArrowLeft')  { e.preventDefault(); goTo(active - 1); }
        if (e.key === 'ArrowRight') { e.preventDefault(); goTo(active + 1); }
        if (e.key === 'Enter' && document.activeElement?.closest('#nav-carousel')) {
            goTo(active, true);
        }
    });

    /* ── Dot clicks ──────────────────────────────────────────── */
    DOTS.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));

    /* ── Init: positions already server-rendered, only sync dots */
    syncDots();
})();
</script>
@endpush
