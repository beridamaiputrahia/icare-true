@extends('layouts.app')

@section('title', 'Kartu Anggota — ' . $member->nama_lengkap)
@section('page-title', 'Kartu Anggota Digital')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}" class="text-decoration-none text-muted">Anggota</a></li>
    <li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}" class="text-decoration-none text-muted">{{ $member->nama_lengkap }}</a></li>
    <li class="breadcrumb-item active">QR Code</li>
@endsection

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('qr.download', $member) }}" class="btn btn-sm btn-outline-primary">
        <i class="fa-solid fa-download me-1"></i>Download PNG
    </a>
    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
        <i class="fa-solid fa-print me-1"></i>Cetak
    </button>
    @php $waNum = preg_replace('/\D/', '', $member->nomor_hp ?? ''); @endphp
    @if($waNum)
    <a href="https://wa.me/{{ $waNum }}?text={{ urlencode('Halo! Ini kartu anggota digital ' . $member->nama_lengkap . ' dari ' . config('app.name') . '. No. Anggota: ' . $memberNo) }}"
       target="_blank" class="btn btn-sm btn-success">
        <i class="fa-brands fa-whatsapp me-1"></i>Bagikan
    </a>
    @endif
</div>
@endsection

@push('styles')
<style>
    @media print {
        #sidebar, #topbar, .wa-float, #pwa-banner, .btn, nav { display: none !important; }
        #main { margin-left: 0 !important; }
        .content-wrapper { padding: 0 !important; }
        .member-card-wrap { display: flex; justify-content: center; }
    }
    .member-card {
        width: 380px; max-width: 100%;
        border-radius: 20px; overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,.18);
        background: #fff; position: relative;
    }
    .card-header-banner {
        background: linear-gradient(135deg, var(--app-primary,#2563eb), #7c3aed);
        padding: 2rem 1.5rem 3.5rem; text-align: center; position: relative;
    }
    .card-avatar {
        width: 90px; height: 90px; border-radius: 50%;
        border: 4px solid rgba(255,255,255,.5);
        background: rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 2.5rem; color: #fff; font-weight: 700;
        overflow: hidden;
    }
    .card-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .card-name { color: #fff; font-size: 1.2rem; font-weight: 700; margin: 0; }
    .card-nickname { color: rgba(255,255,255,.75); font-size: .85rem; }
    .card-body-inner { padding: 1rem 1.5rem 1.5rem; }
    .card-member-no {
        background: var(--app-primary,#2563eb); color: #fff;
        text-align: center; padding: .35rem 1rem; border-radius: 20px;
        font-size: .78rem; font-weight: 600; letter-spacing: .08em;
        display: inline-block; margin-bottom: 1rem;
    }
    .qr-wrap { display: flex; justify-content: center; margin-bottom: 1rem; }
    .qr-wrap svg { border-radius: 12px; border: 2px solid #e2e8f0; padding: 8px; background: #fff; }
    .badge-row { display: flex; flex-wrap: wrap; gap: .35rem; justify-content: center; margin-bottom: .75rem; }
    .card-footer-strip {
        background: #f8fafc; border-top: 1px solid #e2e8f0;
        padding: .65rem 1.5rem; text-align: center;
        font-size: .7rem; color: #94a3b8; font-weight: 500;
    }
    .info-row { display: flex; align-items: center; gap: .5rem; margin-bottom: .45rem; font-size: .82rem; }
    .info-row i { width: 18px; color: var(--app-primary,#2563eb); flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

        {{-- Member Card ──────────────────────────────────────────── --}}
        <div class="member-card-wrap d-flex justify-content-center mb-4">
        <div class="member-card">

            {{-- Header --}}
            <div class="card-header-banner">
                <div class="card-avatar">
                    @if($member->foto)
                        <img src="{{ Storage::url($member->foto) }}" alt="{{ $member->nama_lengkap }}">
                    @else
                        {{ strtoupper(substr($member->nama_lengkap, 0, 1)) }}
                    @endif
                </div>
                <p class="card-name">{{ $member->nama_lengkap }}</p>
                @if($member->nama_panggilan)
                <p class="card-nickname">"{{ $member->nama_panggilan }}"</p>
                @endif
            </div>

            {{-- Body --}}
            <div class="card-body-inner text-center">
                <span class="card-member-no">{{ $memberNo }}</span>

                {{-- QR Code --}}
                <div class="qr-wrap">
                    {!! $qrSvg !!}
                </div>

                {{-- Member Info --}}
                <div class="text-start mb-3">
                    <div class="info-row">
                        <i class="fa-solid fa-circle-dot"></i>
                        <span><strong>Status:</strong>
                            <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-secondary' }} ms-1">
                                {{ $member->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </span>
                    </div>
                    @if($member->kelompok)
                    <div class="info-row">
                        <i class="fa-solid fa-people-group"></i>
                        <span><strong>Kelompok:</strong> {{ $member->kelompok }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <i class="fa-regular fa-calendar"></i>
                        <span><strong>Bergabung:</strong> {{ $member->created_at->translatedFormat('F Y') }}</span>
                    </div>
                </div>

                {{-- Banner & Achievement --}}
                @php
                    $user     = \App\Models\User::where('name', $member->nama_lengkap)->first();
                    $banner   = $user?->banner;
                    $topBadge = $user?->highest_achievement;
                @endphp

                @if($banner || $topBadge)
                <div class="badge-row">
                    @if($banner)
                    <span class="badge" style="background:{{ $banner->bg_color }};color:{{ $banner->text_color }}">
                        <i class="fa-solid {{ $banner->icon }} me-1"></i>{{ $banner->name }}
                    </span>
                    @endif
                    @if($topBadge)
                    <span class="badge" style="background:{{ $topBadge->color }}20;color:{{ $topBadge->color }};border:1px solid {{ $topBadge->color }}40">
                        <i class="fa-solid {{ $topBadge->icon }} me-1"></i>{{ $topBadge->name }}
                    </span>
                    @endif
                </div>
                @endif

                {{-- Level badge if user exists --}}
                @if($user && $user->level > 1)
                @php $lvlProgress = $user->level_progress; @endphp
                <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fa-solid {{ $lvlProgress['current_icon'] }}" style="color:{{ $lvlProgress['current_color'] }}"></i>
                    <span style="font-size:.78rem;font-weight:600;color:{{ $lvlProgress['current_color'] }}">
                        Level {{ $user->level }} — {{ $lvlProgress['current_name'] }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Footer strip --}}
            <div class="card-footer-strip">
                <i class="fa-solid fa-cross me-1"></i>{{ config('app.name') }} &bull; Scan untuk verifikasi
            </div>
        </div>
        </div>

        {{-- Action Buttons (non-print) --}}
        <div class="d-flex gap-2 justify-content-center flex-wrap d-print-none">
            <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Profil
            </a>
            <a href="{{ route('qr.download', $member) }}" class="btn btn-primary">
                <i class="fa-solid fa-download me-1"></i>Download QR Code
            </a>
        </div>

    </div>
</div>
@endsection
