@extends('layouts.app')

@section('title', 'Poin Saya')
@section('page-title', 'Sistem Poin Komunitas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Poin Saya</li>
@endsection

@push('styles')
<style>
.level-progress-wrap { background:#fff; border-radius:14px; border:1px solid #e2e8f0; padding:1.5rem; }
.level-badge-big {
    width:72px; height:72px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-size:1.8rem; margin: 0 auto .75rem; box-shadow:0 4px 16px rgba(0,0,0,.15);
}
.progress { height:10px; border-radius:10px; }
.pt-row {
    display:flex; align-items:center; gap:.85rem; padding:.75rem 1rem;
    border-bottom:1px solid #f1f5f9; transition:background .15s;
}
.pt-row:last-child { border-bottom:none; }
.pt-row:hover { background:#f8fafc; }
.pt-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.pt-val { font-weight:700; font-size:1rem; color:#22c55e; margin-left:auto; flex-shrink:0; }
.stat-mini-card {
    background:#fff; border-radius:12px; border:1px solid #e2e8f0;
    padding:1rem; text-align:center;
}
.stat-mini-card .val { font-size:1.6rem; font-weight:700; line-height:1; color:var(--app-primary,#2563eb); }
.stat-mini-card .lbl { font-size:.72rem; color:#94a3b8; margin-top:.2rem; }
</style>
@endpush

@section('content')

{{-- Stats Row ────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="val">{{ number_format($user->total_points) }}</div>
            <div class="lbl"><i class="fa-solid fa-coins me-1 text-warning"></i>Total Poin</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="val">{{ number_format($weekPoints) }}</div>
            <div class="lbl"><i class="fa-solid fa-calendar-week me-1 text-primary"></i>Poin Minggu Ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="val">{{ number_format($monthPoints) }}</div>
            <div class="lbl"><i class="fa-solid fa-calendar me-1 text-success"></i>Poin Bulan Ini</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card">
            <div class="val">{{ $user->level }}</div>
            <div class="lbl"><i class="fa-solid fa-layer-group me-1 text-info"></i>Level Saat Ini</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Level Progress ───────────────────────────────────────── --}}
    <div class="col-12 col-lg-4">
        <div class="level-progress-wrap text-center mb-4">
            <div class="level-badge-big" style="background:{{ $progress['current_color'] }}20">
                <i class="fa-solid {{ $progress['current_icon'] }}" style="color:{{ $progress['current_color'] }}"></i>
            </div>
            <h5 class="fw-bold mb-0" style="color:{{ $progress['current_color'] }}">{{ $progress['current_name'] }}</h5>
            <p class="text-muted small mb-3">Level {{ $progress['current_level'] }}</p>

            @if(!$progress['is_max'])
            <div class="mb-2 d-flex justify-content-between" style="font-size:.75rem;color:#64748b">
                <span>{{ number_format($user->total_points) }} poin</span>
                <span>{{ number_format($progress['points_to_next']) }} lagi</span>
            </div>
            <div class="progress mb-3">
                <div class="progress-bar" role="progressbar"
                     style="width:{{ $progress['percent'] }}%;background:{{ $progress['current_color'] }}"
                     aria-valuenow="{{ $progress['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center justify-content-center gap-2" style="font-size:.8rem;color:#64748b">
                <span>Menuju</span>
                <span style="font-weight:600;color:{{ $progress['next_color'] }}">
                    <i class="fa-solid {{ $progress['next_icon'] }} me-1"></i>{{ $progress['next_name'] }}
                </span>
            </div>
            @else
            <div class="alert alert-success border-0 mt-2" style="font-size:.82rem">
                <i class="fa-solid fa-star me-1"></i>Level Tertinggi! Kamu sudah mencapai puncak.
            </div>
            @endif
        </div>

        {{-- All Levels --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-layer-group text-primary me-2"></i>Semua Level</h6>
            </div>
            <div class="card-body p-0">
                @foreach($levels as $lvl => $data)
                <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }}
                     {{ $user->level === $lvl ? 'bg-primary bg-opacity-5' : '' }}">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:{{ $data['color'] }}20">
                        <i class="fa-solid {{ $data['icon'] }}" style="color:{{ $data['color'] }};font-size:.9rem"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size:.82rem;font-weight:600;color:{{ $data['color'] }}">{{ $data['name'] }}</div>
                        <div style="font-size:.7rem;color:#94a3b8">{{ number_format($data['min']) }}+ poin</div>
                    </div>
                    @if($user->level >= $lvl)
                    <i class="fa-solid fa-check-circle text-success" style="font-size:.9rem"></i>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Point History ────────────────────────────────────────── --}}
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>Riwayat Poin</h6>
                <a href="{{ route('leaderboard.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-ranking-star me-1"></i>Leaderboard
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($points as $pt)
                <div class="pt-row">
                    <div class="pt-icon bg-{{ $pt->type_color }} bg-opacity-15">
                        <i class="fa-solid {{ $pt->type_icon }} text-{{ $pt->type_color }}"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fw-semibold text-truncate" style="font-size:.85rem">{{ $pt->label }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem">{{ $pt->created_at->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div class="pt-val">+{{ $pt->points }}</div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-coins fa-2x mb-2 d-block opacity-25"></i>
                    <p class="small mb-0">Belum ada riwayat poin.</p>
                    <p class="small">Upload renungan atau kirim doa untuk mulai mengumpulkan poin!</p>
                </div>
                @endforelse
            </div>
            @if($points->hasPages())
            <div class="card-footer border-top-0 bg-white">
                {{ $points->links() }}
            </div>
            @endif
        </div>

        {{-- How to earn points --}}
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-circle-info text-info me-2"></i>Cara Mendapatkan Poin</h6>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach([
                        ['icon'=>'fa-book-open-reader','color'=>'warning','label'=>'Upload Renungan','pts'=>10],
                        ['icon'=>'fa-share-from-square','color'=>'primary','label'=>'Sharing Firman (Disetujui)','pts'=>15],
                        ['icon'=>'fa-hands-praying','color'=>'info','label'=>'Kirim Pokok Doa','pts'=>3],
                        ['icon'=>'fa-trophy','color'=>'success','label'=>'Mendapat Achievement','pts'=>20],
                    ] as $rule)
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc">
                            <div class="rounded-2 p-2 bg-{{ $rule['color'] }} bg-opacity-15">
                                <i class="fa-solid {{ $rule['icon'] }} text-{{ $rule['color'] }}" style="font-size:.9rem"></i>
                            </div>
                            <div>
                                <div style="font-size:.78rem;font-weight:600">{{ $rule['label'] }}</div>
                                <div style="font-size:.72rem;color:#22c55e;font-weight:700">+{{ $rule['pts'] }} poin</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
