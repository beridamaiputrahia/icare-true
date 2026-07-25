<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ $_settings->primaryColor() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $_appName }}">
    <meta name="application-name" content="{{ $_appName }}">
    <title>@yield('title', 'Dashboard') — {{ $_appName }}</title>

    {{-- PWA --}}
    <link rel="manifest" href="{{ route('manifest') }}" crossorigin="use-credentials">
    <link rel="apple-touch-icon" href="{{ $_settings->logoIconUrl() ?? '/pwa-icons/icon-192.svg' }}">
    @if($_settings->faviconUrl())
    <link rel="icon" href="{{ $_settings->faviconUrl() }}">
    @endif

    {{-- CSS CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- Dynamic Theme --}}
    <style>{!! app(\App\Managers\ThemeManager::class)->generateCss() !!}</style>

    <style>
    /* ═══════════════════════════════════════════════════════
       GLOBAL BASE — LIGHT THEME (default)
       ═══════════════════════════════════════════════════════ */
    :root,
    [data-theme="light"] {
        --body-bg:         #f1f5f9;
        --surface:         #ffffff;
        --surface-2:       #f8fafc;
        --border:          #e2e8f0;
        --text:            #1e293b;
        --text-muted:      #64748b;
        --text-subtle:     #94a3b8;
        --card-shadow:     0 1px 3px rgba(0,0,0,.10);
        --topbar-h:        60px;
        --nav-h:           110px;
        --input-border:    #d1d5db;
        --badge-pending-bg:#fef3c7;  --badge-pending-fg:#92400e;
        --badge-approved-bg:#d1fae5; --badge-approved-fg:#065f46;
        --badge-answered-bg:#ede9fe; --badge-answered-fg:#5b21b6;
        --badge-rejected-bg:#fee2e2; --badge-rejected-fg:#991b1b;
        --badge-upcoming-bg:#dbeafe; --badge-upcoming-fg:#1e40af;
        --badge-done-bg:   #f3f4f6;  --badge-done-fg:#374151;
        --table-header-bg: #f8fafc;
        --theme-toggle-icon: '☀️';
    }

    /* ═══════════════════════════════════════════════════════
       DARK THEME
       ═══════════════════════════════════════════════════════ */
    [data-theme="dark"] {
        --body-bg:         #0f172a;
        --surface:         #1e293b;
        --surface-2:       #162032;
        --border:          #334155;
        --text:            #f1f5f9;
        --text-muted:      #94a3b8;
        --text-subtle:     #64748b;
        --card-shadow:     0 1px 6px rgba(0,0,0,.4);
        --input-border:    #475569;
        --badge-pending-bg:#451a03;  --badge-pending-fg:#fcd34d;
        --badge-approved-bg:#052e16; --badge-approved-fg:#6ee7b7;
        --badge-answered-bg:#2e1065; --badge-answered-fg:#c4b5fd;
        --badge-rejected-bg:#450a0a; --badge-rejected-fg:#fca5a5;
        --badge-upcoming-bg:#0c1a4b; --badge-upcoming-fg:#93c5fd;
        --badge-done-bg:   #1e293b;  --badge-done-fg:#94a3b8;
        --table-header-bg: #162032;
        --theme-toggle-icon: '🌙';
    }

    [data-theme="dark"] body { color-scheme: dark; }
    [data-theme="dark"] .bg-white,
    [data-theme="dark"] .card,
    [data-theme="dark"] .modal-content { background: var(--surface) !important; color: var(--text) !important; }
    [data-theme="dark"] .card-header  { background: var(--surface) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .card-footer  { background: var(--surface-2) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .table        { --bs-table-bg: var(--surface); --bs-table-color: var(--text); --bs-table-border-color: var(--border); }
    [data-theme="dark"] .table th     { background: var(--table-header-bg) !important; color: var(--text-muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .table td     { border-color: var(--border) !important; }
    [data-theme="dark"] .table-hover tbody tr:hover { --bs-table-accent-bg: rgba(255,255,255,.04); }
    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select  { background: var(--surface-2); border-color: var(--input-border); color: var(--text); }
    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus { background: var(--surface); border-color: var(--app-primary,#3b82f6); color: var(--text); box-shadow: 0 0 0 .2rem rgba(59,130,246,.25); }
    [data-theme="dark"] .form-control::placeholder { color: var(--text-subtle); }
    [data-theme="dark"] .form-label   { color: var(--text-muted); }
    [data-theme="dark"] .input-group-text { background: var(--surface-2); border-color: var(--input-border); color: var(--text-muted); }
    [data-theme="dark"] .btn-light    { background: var(--surface-2); border-color: var(--border); color: var(--text); }
    [data-theme="dark"] .btn-light:hover { background: var(--border); }
    [data-theme="dark"] .btn-outline-secondary { border-color: var(--border); color: var(--text-muted); }
    [data-theme="dark"] .btn-outline-secondary:hover { background: var(--surface-2); color: var(--text); }
    [data-theme="dark"] .dropdown-menu { background: var(--surface); border-color: var(--border); }
    [data-theme="dark"] .dropdown-item { color: var(--text); }
    [data-theme="dark"] .dropdown-item:hover { background: var(--surface-2); }
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer { border-color: var(--border); }
    [data-theme="dark"] .alert-success { background: #052e16; color: #6ee7b7; border-color: #065f46; }
    [data-theme="dark"] .alert-danger  { background: #450a0a; color: #fca5a5; border-color: #991b1b; }
    [data-theme="dark"] .alert-warning { background: #451a03; color: #fcd34d; border-color: #92400e; }
    [data-theme="dark"] .alert-info    { background: #0c1a4b; color: #93c5fd; border-color: #1e40af; }
    [data-theme="dark"] .text-muted    { color: var(--text-muted) !important; }
    [data-theme="dark"] .text-dark     { color: var(--text) !important; }
    [data-theme="dark"] .border        { border-color: var(--border) !important; }
    [data-theme="dark"] .border-bottom,
    [data-theme="dark"] .border-top    { border-color: var(--border) !important; }
    [data-theme="dark"] hr             { border-color: var(--border); }
    [data-theme="dark"] .list-group-item { background: var(--surface); border-color: var(--border); color: var(--text); }
    [data-theme="dark"] .pagination .page-link { background: var(--surface); border-color: var(--border); color: var(--text-muted); }
    [data-theme="dark"] .pagination .page-item.active .page-link { background: var(--app-primary,#2563eb); border-color: var(--app-primary,#2563eb); }
    [data-theme="dark"] .birthday-card { background: #1e1505 !important; }

    /* Role badge warna — dark override */
    [data-theme="dark"] .badge.bg-warning { color: #0f172a !important; }

    *, *::before, *::after { box-sizing: border-box; }
    body {
        font-family: 'Inter', sans-serif;
        background: var(--body-bg);
        color: var(--text);
        overflow-x: hidden;
        padding-bottom: var(--nav-h);
        transition: background .25s, color .25s;
    }

    /* ═══════════════════════════════════════════════════════
       TOPBAR
       ═══════════════════════════════════════════════════════ */
    #topbar {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 0 1.25rem;
        height: var(--topbar-h);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 900;
        box-shadow: 0 1px 8px rgba(0,0,0,.06);
        gap: 1rem;
        transition: background .25s, border-color .25s;
    }

    /* Brand (left side of topbar) */
    .topbar-brand {
        display: flex;
        align-items: center;
        gap: .6rem;
        text-decoration: none;
        flex-shrink: 0;
    }
    .topbar-brand-icon {
        width: 34px; height: 34px;
        background: var(--app-primary, #2563eb);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; overflow: hidden;
    }
    .topbar-brand-name {
        font-weight: 700; font-size: .88rem;
        color: var(--text); white-space: nowrap;
        line-height: 1.1;
    }
    .topbar-brand-sub {
        font-size: .62rem; color: var(--text-subtle);
        display: block; line-height: 1;
    }

    /* Page title (center area) */
    .topbar-title-wrap { flex: 1; min-width: 0; }
    .page-title-text {
        font-weight: 600; color: var(--text);
        margin: 0; font-size: 1rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* Notification bell badge */
    .notif-badge {
        position: absolute; top: -4px; right: -4px;
        background: #ef4444; color: #fff; border-radius: 50%;
        width: 16px; height: 16px; font-size: .55rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
    }

    /* Dark mode toggle button */
    #theme-toggle {
        background: none; border: 1px solid var(--border);
        border-radius: 8px; padding: 4px 8px; cursor: pointer;
        font-size: .95rem; line-height: 1; color: var(--text-muted);
        display: flex; align-items: center; justify-content: center;
        transition: border-color .2s, background .2s;
    }
    #theme-toggle:hover { background: var(--surface-2); border-color: var(--text-subtle); }

    /* ═══════════════════════════════════════════════════════
       CONTENT WRAPPER
       ═══════════════════════════════════════════════════════ */
    .content-wrapper { padding: 1.5rem; }

    /* ═══════════════════════════════════════════════════════
       CARDS & COMPONENTS
       ═══════════════════════════════════════════════════════ */
    .stat-card {
        background: var(--surface); border-radius: 12px; padding: 1.25rem;
        box-shadow: var(--card-shadow); border: 1px solid var(--border);
        transition: transform .2s, box-shadow .2s; height: 100%;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
    .stat-icon  { width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; }
    .stat-value { font-size:1.75rem; font-weight:700; color:var(--app-primary,#1e293b); }
    .stat-label { font-size:.78rem; color:var(--text-muted); font-weight:500; }

    .card { border:1px solid var(--border); border-radius:12px; box-shadow:var(--card-shadow); background:var(--surface); }
    .card-header { background:var(--surface); border-bottom:1px solid var(--border); padding:1rem 1.25rem; border-radius:12px 12px 0 0 !important; }

    .table th  { font-size:.75rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:.05em; background:var(--table-header-bg); }
    .table td  { font-size:.875rem; vertical-align:middle; color:var(--text); }
    .table > :not(caption) > * > * { padding:.75rem 1rem; }

    .badge         { font-weight:500; font-size:.72rem; padding:.35em .65em; border-radius:6px; }
    .badge-pending  { background:var(--badge-pending-bg);  color:var(--badge-pending-fg); }
    .badge-approved { background:var(--badge-approved-bg); color:var(--badge-approved-fg); }
    .badge-answered { background:var(--badge-answered-bg); color:var(--badge-answered-fg); }
    .badge-rejected { background:var(--badge-rejected-bg); color:var(--badge-rejected-fg); }
    .badge-upcoming { background:var(--badge-upcoming-bg); color:var(--badge-upcoming-fg); }
    .badge-ongoing  { background:var(--badge-approved-bg); color:var(--badge-approved-fg); }
    .badge-done     { background:var(--badge-done-bg);     color:var(--badge-done-fg); }

    .countdown-box {
        background: linear-gradient(135deg, var(--app-primary,#2563eb), #7c3aed);
        border-radius:12px; padding:1.25rem 1.5rem; color:#fff;
    }
    .count-number { font-size:2rem; font-weight:700; line-height:1; }
    .count-label  { font-size:.65rem; text-transform:uppercase; opacity:.8; }

    .verse-card {
        background: linear-gradient(135deg, #0f172a, #1e3a5f);
        border-radius:12px; padding:1.5rem; color:#fff;
    }
    .verse-text { font-size:.95rem; font-style:italic; line-height:1.8; }
    .verse-ref  { font-size:.78rem; opacity:.7; font-weight:500; }

    .btn { border-radius:8px; font-weight:500; font-size:.875rem; }
    .btn-sm { font-size:.78rem; }
    .form-control, .form-select, .form-check-input { border-radius:8px; font-size:.875rem; border-color:var(--input-border); }
    .form-label  { font-size:.825rem; font-weight:500; color:var(--text-muted); margin-bottom:.35rem; }
    .alert       { border-radius:10px; border:none; }
    .input-group-text { border-radius:8px; font-size:.875rem; }

    .member-photo    { width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid var(--border); }
    .member-photo-lg { width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid var(--border); }
    .avatar-placeholder { border-radius:50%; background:var(--surface-2); display:inline-flex; align-items:center; justify-content:center; color:var(--text-muted); }

    /* ═══════════════════════════════════════════════════════
       BIRTHDAY THEME (active all day on user's birthday)
       ═══════════════════════════════════════════════════════ */
    @if($_isBirthday ?? false)
    :root {
        --app-primary:    #d97706 !important;
        --body-bg:        #fffbeb !important;
    }
    #topbar {
        background: linear-gradient(135deg,#fffbeb,#fef3c7) !important;
        border-bottom-color: #fde68a !important;
    }
    .stat-value               { color:#d97706 !important; }
    .btn-primary              { background:#d97706 !important; border-color:#d97706 !important; }
    .btn-primary:hover        { background:#b45309 !important; border-color:#b45309 !important; }
    .birthday-top-banner {
        background: linear-gradient(135deg,#f59e0b,#d97706);
        color:#fff; font-size:.82rem; font-weight:600;
        padding:.45rem 1.5rem; display:flex; align-items:center;
        justify-content:center; gap:.5rem; letter-spacing:.01em;
    }
    @endif

    /* ═══════════════════════════════════════════════════════
       BIRTHDAY POPUP
       ═══════════════════════════════════════════════════════ */
    .birthday-backdrop {
        position:fixed; inset:0; background:rgba(0,0,0,.72);
        z-index:9999; display:flex; align-items:center; justify-content:center;
    }
    .birthday-card {
        background:#fff; border-radius:24px; padding:2.5rem 2rem;
        max-width:420px; width:90%; text-align:center;
        animation:bounceIn .6s ease; position:relative; overflow:hidden;
    }
    @keyframes bounceIn {
        0%   { transform:scale(.5); opacity:0; }
        80%  { transform:scale(1.05); }
        100% { transform:scale(1); opacity:1; }
    }
    #confetti-canvas { position:fixed; inset:0; pointer-events:none; z-index:9998; }

    /* ═══════════════════════════════════════════════════════
       WHATSAPP FLOAT
       ═══════════════════════════════════════════════════════ */
    .wa-float {
        position:fixed; bottom:calc(var(--nav-h) + 12px); right:20px;
        z-index:800; display:flex; flex-direction:column; gap:10px; align-items:flex-end;
    }
    .wa-btn {
        width:52px; height:52px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        font-size:1.4rem; color:#fff; text-decoration:none;
        box-shadow:0 4px 12px rgba(0,0,0,.2); transition:transform .2s,box-shadow .2s;
    }
    .wa-btn:hover { transform:scale(1.1); box-shadow:0 6px 20px rgba(0,0,0,.3); color:#fff; }
    .wa-btn.leader { background:#25d366; }
    .wa-btn.co     { background:#128c7e; }
    .wa-label {
        background:rgba(0,0,0,.7); color:#fff; font-size:.72rem;
        padding:.2rem .6rem; border-radius:10px; white-space:nowrap; margin-right:4px;
    }

    /* ═══════════════════════════════════════════════════════
       PWA INSTALL BANNER
       ═══════════════════════════════════════════════════════ */
    #pwa-banner {
        position:fixed; bottom:calc(var(--nav-h) + 8px);
        left:50%; transform:translateX(-50%) translateY(200%);
        width: calc(100% - 32px); max-width: 480px;
        z-index:850; background:var(--app-primary,#2563eb); color:#fff;
        padding:.75rem 1.25rem; display:flex; align-items:center; gap:1rem;
        border-radius:16px; box-shadow:0 8px 32px rgba(0,0,0,.25);
        transition:transform .4s cubic-bezier(.34,1.56,.64,1);
    }
    #pwa-banner.show { transform:translateX(-50%) translateY(0); }

    /* ═══════════════════════════════════════════════════════
       RESPONSIVE
       ═══════════════════════════════════════════════════════ */
    @media (max-width: 767.98px) {
        .content-wrapper { padding:1rem; }
        #topbar { padding:0 .875rem; }
        .topbar-brand-name { display:none; } /* hide brand name on very small, icon only */
    }
    @media (max-width: 575.98px) {
        .topbar-brand-sub { display:none; }
    }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Birthday confetti canvas --}}
    @if($_isBirthday ?? false)
    <canvas id="confetti-canvas"></canvas>
    @endif

    {{-- ══════════════════════════════════════════════════════
         TOPBAR
         ══════════════════════════════════════════════════════ --}}
    <header id="topbar">
        {{-- Brand --}}
        <a href="{{ route('dashboard') }}" class="topbar-brand">
            <div class="topbar-brand-icon">
                @if($_settings->logoUrl())
                <img src="{{ $_settings->logoUrl() }}" alt="Logo" style="width:100%;height:100%;object-fit:contain">
                @else
                <i class="fa-solid fa-cross text-white" style="font-size:.8rem"></i>
                @endif
            </div>
            <div class="d-none d-sm-block">
                <span class="topbar-brand-name">{{ $_appName }}</span>
                <span class="topbar-brand-sub">{{ $_settings->get('app_tagline','Komunitas Rohani') }}</span>
            </div>
        </a>

        {{-- Page Title --}}
        <div class="topbar-title-wrap d-none d-md-block">
            <p class="page-title-text mb-0">@yield('page-title', 'Dashboard')</p>
        </div>

        {{-- Right Actions --}}
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
            @yield('page-actions')

            {{-- Notification Bell --}}
            <div class="position-relative">
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light position-relative" title="Notifikasi" id="notifBellLink">
                    <i class="fa-solid fa-bell"></i>
                    <span class="notif-badge" id="notifBellBadge" style="{{ $_notifCount > 0 ? '' : 'display:none' }}">{{ $_notifCount > 9 ? '9+' : $_notifCount }}</span>
                </a>
            </div>

            {{-- Dark mode toggle --}}
            <button id="theme-toggle" title="Ganti tema" aria-label="Toggle dark mode">
                <span id="theme-icon">🌙</span>
            </button>

            {{-- Role badge --}}
            @php $u = auth()->user(); @endphp
            <span class="badge bg-{{ $u->roleColor() }} d-none d-sm-inline"
                  style="font-size:.62rem;text-transform:uppercase;letter-spacing:.05em"
                  title="{{ $u->roleLabel() }}">
                {{ $u->roleLabel() }}
            </span>

            {{-- Profile --}}
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                <i class="fa-solid fa-user-circle"></i>
                <span class="d-none d-lg-inline">{{ Str::limit(auth()->user()->name, 14) }}</span>
            </a>
        </div>
    </header>

    {{-- Birthday banners --}}
    @if($_isBirthday ?? false)
    <div class="birthday-top-banner">
        🎂 Selamat Ulang Tahun, <strong>{{ $_birthdayName }}</strong>! Semoga hari ini penuh berkat! 🎉✨
    </div>
    @elseif(($_birthdayMembers ?? collect())->isNotEmpty())
    <div style="background:linear-gradient(135deg,#fef3c7,#fde68a);border-bottom:1px solid #fcd34d;padding:.45rem 1.5rem;font-size:.82rem;font-weight:500;color:#92400e;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap">
        🎂 <strong>Ulang Tahun Hari Ini:</strong>
        @foreach($_birthdayMembers as $bMember)
        <span class="badge" style="background:#f59e0b;color:#fff">{{ $bMember->nama_panggilan ?: $bMember->nama_lengkap }}</span>
        @endforeach
        — Jangan lupa ucapkan selamat! 🎉
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MAIN CONTENT
         ══════════════════════════════════════════════════════ --}}
    <main>
        <div class="content-wrapper">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="fa-solid fa-circle-check fa-lg flex-shrink-0"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="fa-solid fa-circle-exclamation fa-lg flex-shrink-0"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- WhatsApp Float --}}
    @php $waLeader = $_settings->waLeader(); $waCoLeader = $_settings->waCoLeader(); @endphp
    @if($waLeader || $waCoLeader)
    <div class="wa-float">
        @if($waLeader)
        <div class="d-flex align-items-center gap-2">
            <span class="wa-label">Leader</span>
            <a href="https://wa.me/{{ preg_replace('/\D/','',$waLeader) }}" target="_blank" rel="noopener"
               class="wa-btn leader" title="Hubungi Leader">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        </div>
        @endif
        @if($waCoLeader)
        <div class="d-flex align-items-center gap-2">
            <span class="wa-label">Co-Leader</span>
            <a href="https://wa.me/{{ preg_replace('/\D/','',$waCoLeader) }}" target="_blank" rel="noopener"
               class="wa-btn co" title="Hubungi Co-Leader">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- PWA Install Banner --}}
    <div id="pwa-banner">
        <div class="rounded-3 bg-white d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:38px;height:38px">
            <i class="fa-solid fa-cross" style="color:var(--app-primary,#2563eb);font-size:.9rem"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold" style="font-size:.88rem">Install {{ $_appName }}</div>
            <div style="font-size:.73rem;opacity:.85">Tambahkan ke layar utama untuk akses cepat</div>
        </div>
        <button id="pwa-install-btn" class="btn btn-sm btn-light fw-semibold">Install</button>
        <button id="pwa-dismiss-btn" class="btn btn-sm" style="color:rgba(255,255,255,.75)">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    {{-- Birthday Popup --}}
    @if(session('show_birthday_popup'))
    <div class="birthday-backdrop" id="birthdayBackdrop">
        <div class="birthday-card">
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,#fef3c7,#fde68a,#fef3c7);opacity:.4;border-radius:24px"></div>
            <div style="position:relative;z-index:1">
                <div style="font-size:4rem;line-height:1;margin-bottom:.5rem">🎂</div>
                <h2 class="fw-bold mb-1" style="color:#92400e;font-size:1.6rem">Selamat Ulang Tahun!</h2>
                <h4 class="fw-bold mb-3" style="color:#b45309">{{ session('birthday_name') }} 🎉</h4>
                <div class="p-3 mb-3 rounded-3" style="background:rgba(255,255,255,.8)">
                    <p class="mb-0" style="font-size:.875rem;line-height:1.7;color:#374151;font-style:italic">
                        "Sebab Aku ini mengetahui rancangan-rancangan apa yang ada pada-Ku mengenai kamu, demikianlah firman TUHAN, yaitu rancangan damai sejahtera dan bukan rancangan kecelakaan, untuk memberikan kepadamu hari depan yang penuh harapan."
                    </p>
                    <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size:.78rem">— Yeremia 29:11</p>
                </div>
                <p class="text-muted mb-3" style="font-size:.82rem">
                    Komunitas I Care True mendoakan dan mengucapkan selamat di hari istimewamu!
                </p>
                <button class="btn btn-warning fw-semibold px-4" onclick="document.getElementById('birthdayBackdrop').style.display='none'">
                    <i class="fa-solid fa-heart me-1"></i>Terima Kasih!
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Daily Verse Popup --}}
    @if(session('show_daily_verse_popup'))
    <div class="birthday-backdrop" id="dailyVerseBackdrop">
        <div class="birthday-card">
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,#dbeafe,#bfdbfe,#dbeafe);opacity:.4;border-radius:24px"></div>
            <div style="position:relative;z-index:1">
                <div style="font-size:4rem;line-height:1;margin-bottom:.5rem">📖</div>
                <h2 class="fw-bold mb-3" style="color:#1e40af;font-size:1.5rem">Ayat Hari Ini</h2>
                <div class="p-3 mb-3 rounded-3" style="background:rgba(255,255,255,.8)">
                    <p class="mb-0" style="font-size:.9rem;line-height:1.7;color:#374151;font-style:italic">
                        "{{ session('daily_verse_ayat') }}"
                    </p>
                    <p class="mb-0 mt-2 text-muted fw-semibold" style="font-size:.78rem">— {{ session('daily_verse_referensi') }}</p>
                </div>
                @if(session('daily_verse_renungan'))
                <p class="text-muted mb-3" style="font-size:.82rem;line-height:1.6">
                    {{ session('daily_verse_renungan') }}
                </p>
                @endif
                <button class="btn btn-primary fw-semibold px-4" onclick="document.getElementById('dailyVerseBackdrop').style.display='none'">
                    <i class="fa-solid fa-book-bible me-1"></i>Amin!
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- New Upload Popup --}}
    @if(session('show_upload_popup'))
    <div class="birthday-backdrop" id="uploadPopupBackdrop">
        <div class="birthday-card">
            <div style="position:absolute;inset:0;background:linear-gradient(135deg,#dcfce7,#bbf7d0,#dcfce7);opacity:.4;border-radius:24px"></div>
            <div style="position:relative;z-index:1">
                <div style="font-size:4rem;line-height:1;margin-bottom:.5rem">📸</div>
                <h2 class="fw-bold mb-1" style="color:#166534;font-size:1.4rem">Ada Foto Baru!</h2>
                <p class="mb-3" style="color:#15803d;font-size:.9rem">
                    {{ session('upload_popup_count') }} foto baru
                    @if(session('upload_popup_album'))
                        di album "<strong>{{ session('upload_popup_album') }}</strong>"
                    @endif
                    telah diunggah.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('albums.index') }}" class="btn btn-success fw-semibold px-4">
                        <i class="fa-solid fa-images me-1"></i>Lihat Galeri
                    </a>
                    <button class="btn btn-outline-secondary fw-semibold" onclick="document.getElementById('uploadPopupBackdrop').style.display='none'">
                        Nanti
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         CENTER FOCUS NAVIGATION CAROUSEL
         ══════════════════════════════════════════════════════ --}}
    <x-navigation-carousel />

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <script>
    /* ── DARK MODE ────────────────────────────────────────── */
    (function() {
        const html  = document.documentElement;
        const btn   = document.getElementById('theme-toggle');
        const icon  = document.getElementById('theme-icon');
        const saved = localStorage.getItem('theme') ||
                      (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        function apply(theme) {
            html.setAttribute('data-theme', theme);
            icon.textContent = theme === 'dark' ? '☀️' : '🌙';
            localStorage.setItem('theme', theme);
        }

        apply(saved);
        btn?.addEventListener('click', () => {
            apply(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
        });
    })();

    /* Delete confirmation */
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById(this.dataset.form);
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(r => { if (r.isConfirmed) form.submit(); });
        });
    });

    /* Birthday confetti */
    @if($_isBirthday ?? false)
    (function() {
        const canvas = document.getElementById('confetti-canvas');
        if (!canvas) return;
        function resize() { canvas.width = window.innerWidth; canvas.height = window.innerHeight; }
        resize(); window.addEventListener('resize', resize);
        const ctx = canvas.getContext('2d');
        const colors = ['#f59e0b','#fbbf24','#ec4899','#f472b6','#a855f7','#60a5fa','#34d399','#ef4444'];
        const pieces = Array.from({length:80}, () => ({
            x: Math.random()*canvas.width, y: Math.random()*canvas.height,
            w: Math.random()*9+4, h: Math.random()*4+2,
            color: colors[Math.floor(Math.random()*colors.length)],
            rot: Math.random()*360, vel: Math.random()*1.5+.8,
            rotVel: Math.random()*3-1.5, opacity: Math.random()*.5+.3
        }));
        let running = true;
        function draw() {
            if (!running) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            pieces.forEach(p => {
                p.y += p.vel; p.rot += p.rotVel;
                if (p.y > canvas.height+20) { p.y=-20; p.x=Math.random()*canvas.width; p.vel=Math.random()*1.5+.8; }
                ctx.save(); ctx.globalAlpha=p.opacity;
                ctx.translate(p.x,p.y); ctx.rotate(p.rot*Math.PI/180);
                ctx.fillStyle=p.color; ctx.fillRect(-p.w/2,-p.h/2,p.w,p.h);
                ctx.restore();
            });
            requestAnimationFrame(draw);
        }
        draw();
        setTimeout(() => { running=false; ctx.clearRect(0,0,canvas.width,canvas.height); },
            new Date().setHours(24,0,0,0)-Date.now());
    })();
    @endif

    /* PWA */
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => window.__icareSwRegistration = reg)
            .catch(()=>{});
    }

    /* Push notification subscribe (dipanggil dari tombol "Aktifkan Notifikasi") */
    window.icareEnablePushNotifications = async function () {
        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            alert('Browser ini tidak mendukung notifikasi push.');
            return false;
        }

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return false;

        try {
            const reg = window.__icareSwRegistration || await navigator.serviceWorker.ready;
            const vapidKey = '{{ config('webpush.vapid.public_key') }}';
            if (!vapidKey) return false;

            let subscription = await reg.pushManager.getSubscription();
            if (!subscription) {
                subscription = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidKey),
                });
            }

            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            await fetch('{{ route('push-subscriptions.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify(subscription.toJSON()),
            });

            return true;
        } catch (e) {
            console.error('Gagal subscribe push notification:', e);
            return false;
        }
    };

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = atob(base64);
        return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
    }
    let deferredPrompt;
    const pwaBanner  = document.getElementById('pwa-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    window.addEventListener('beforeinstallprompt', e => {
        e.preventDefault(); deferredPrompt = e;
        if (!sessionStorage.getItem('pwa-dismissed')) setTimeout(() => pwaBanner.classList.add('show'), 2000);
    });
    installBtn?.addEventListener('click', async () => {
        if (deferredPrompt) { deferredPrompt.prompt(); await deferredPrompt.userChoice; deferredPrompt=null; }
        pwaBanner.classList.remove('show');
    });
    dismissBtn?.addEventListener('click', () => {
        pwaBanner.classList.remove('show');
        sessionStorage.setItem('pwa-dismissed','1');
    });

    /* Poll jumlah notifikasi belum dibaca supaya badge lonceng update tanpa
       perlu refresh halaman manual. */
    (function () {
        const badge = document.getElementById('notifBellBadge');
        if (!badge) return;

        async function refreshCount() {
            try {
                const r = await fetch('{{ route('notifications.count') }}', { headers: { 'Accept': 'application/json' } });
                if (!r.ok) return;
                const data = await r.json();
                if (data.count > 0) {
                    badge.textContent = data.count > 9 ? '9+' : data.count;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            } catch (e) { /* diam-diam abaikan, coba lagi di interval berikutnya */ }
        }

        setInterval(refreshCount, 20000);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') refreshCount();
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
