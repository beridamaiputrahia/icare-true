@extends('layouts.app')

@section('title', 'Achievement')
@section('page-title', 'Achievement & Badge')
@section('breadcrumb')
    <li class="breadcrumb-item active">Achievement</li>
@endsection

@push('styles')
<style>
.badge-card {
    border-radius: 12px; padding: 1.25rem; text-align: center;
    transition: transform .2s, box-shadow .2s;
    border: 2px solid transparent;
}
.badge-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
.badge-card.achieved { border-color: #22c55e; }
.badge-card.locked { opacity: .5; filter: grayscale(1); }
.badge-icon-wrap {
    width: 64px; height: 64px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .75rem; font-size: 1.5rem;
}
.progress { height: 8px; border-radius: 8px; }
.user-banner {
    border-radius: 14px; padding: 1.5rem 2rem;
    display: flex; align-items: center; gap: 1rem;
    color: #fff;
}
</style>
@endpush

@section('content')

{{-- User Banner & Summary --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-7">
        @php $banner = auth()->user()->banner; @endphp
        @if($banner)
        <div class="user-banner" style="{{ $banner->gradient_style }}">
            <div style="font-size:2rem;opacity:.9"><i class="fa-solid {{ $banner->icon }}"></i></div>
            <div>
                <div style="font-size:.7rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Banner Saat Ini</div>
                <h4 class="fw-bold mb-0" style="color:{{ $banner->text_color }}">{{ $banner->label }}</h4>
                <p class="mb-0" style="font-size:.82rem;opacity:.85">{{ $banner->description }}</p>
            </div>
        </div>
        @else
        <div class="user-banner" style="background:linear-gradient(135deg,#64748b,#334155)">
            <div style="font-size:2rem;opacity:.8"><i class="fa-solid fa-user"></i></div>
            <div>
                <div style="font-size:.7rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Banner Saat Ini</div>
                <h4 class="fw-bold mb-0">Anggota</h4>
            </div>
        </div>
        @endif
    </div>
    <div class="col-12 col-md-5">
        <div class="stat-card h-100">
            <h6 class="fw-semibold text-muted mb-3" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.05em">Ringkasan Badge</h6>
            <div class="d-flex gap-3">
                <div class="text-center">
                    <div class="stat-value text-primary">{{ $myBadges->count() }}</div>
                    <div class="stat-label">Badge Diraih</div>
                </div>
                <div class="text-center">
                    <div class="stat-value text-warning">{{ $allBadges->count() - $myBadges->count() }}</div>
                    <div class="stat-label">Belum Diraih</div>
                </div>
                <div class="text-center">
                    <div class="stat-value text-success">{{ $allBadges->count() }}</div>
                    <div class="stat-label">Total Badge</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Progress Section --}}
<div class="row g-3 mb-4">
    @foreach($progress as $category => $data)
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    @if($category === 'sharing_firman')
                        <i class="fa-solid fa-cross text-primary me-2"></i>Progress Sharing Firman
                    @else
                        <i class="fa-solid fa-book-open text-warning me-2"></i>Progress Renungan
                    @endif
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:.82rem;color:#64748b">Jumlah saat ini</span>
                    <span class="fw-bold" style="font-size:1.1rem;color:#1e293b">{{ $data['count'] }}</span>
                </div>

                @foreach($data['steps'] as $step)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.78rem;font-weight:{{ $step['achieved'] ? '600':'500' }};color:{{ $step['achieved'] ? '#22c55e':'#64748b' }}">
                            @if($step['achieved'])<i class="fa-solid fa-circle-check me-1"></i>@endif
                            {{ $step['label'] }}
                        </span>
                        <span style="font-size:.75rem;color:#94a3b8">{{ $data['count'] }}/{{ $step['required'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar {{ $step['achieved'] ? 'bg-success' : 'bg-primary' }}"
                             style="width:{{ $step['percent'] }}%" role="progressbar"></div>
                    </div>
                </div>
                @endforeach

                @if(!$data['complete'] && $data['next'])
                <div class="mt-3 p-2 rounded-3 bg-primary bg-opacity-5">
                    <p class="mb-0 text-primary" style="font-size:.78rem">
                        <i class="fa-solid fa-bullseye me-1"></i>
                        Butuh <strong>{{ $data['next']['required'] - $data['count'] }}x</strong> lagi untuk meraih badge
                        <strong>{{ $data['next']['label'] }}</strong>
                    </p>
                </div>
                @elseif($data['complete'])
                <div class="mt-3 p-2 rounded-3" style="background:#d1fae5">
                    <p class="mb-0 text-success" style="font-size:.78rem">
                        <i class="fa-solid fa-trophy me-1"></i>Semua badge sudah diraih! Luar biasa!
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- All Badges --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-trophy text-warning me-2"></i>Semua Badge</h6>
    </div>
    <div class="card-body">
        @foreach(['sharing_firman' => 'Sharing Firman', 'renungan' => 'Renungan'] as $cat => $catLabel)
        <h6 class="fw-semibold text-muted mb-3" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.06em">
            {{ $catLabel }}
        </h6>
        <div class="row g-3 mb-4">
            @foreach($allBadges->where('category', $cat) as $badge)
            @php $achieved = $myBadges->contains('id', $badge->id); @endphp
            <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                <div class="badge-card stat-card {{ $achieved ? 'achieved' : 'locked' }}">
                    <div class="badge-icon-wrap" style="background:{{ $badge->color }}22">
                        <i class="fa-solid {{ $badge->icon }}" style="color:{{ $badge->color }}"></i>
                    </div>
                    <div class="fw-semibold mb-1" style="font-size:.825rem">{{ $badge->badge_label }}</div>
                    <div class="text-muted" style="font-size:.72rem">{{ $badge->required_count }}x {{ $cat === 'sharing_firman' ? 'disetujui' : 'renungan' }}</div>
                    @if($achieved)
                    <div class="mt-2">
                        <span class="badge bg-success" style="font-size:.65rem"><i class="fa-solid fa-check me-1"></i>Diraih</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
@endsection
