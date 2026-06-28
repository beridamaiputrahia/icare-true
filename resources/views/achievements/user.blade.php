@extends('layouts.app')

@section('title', 'Achievement - ' . $user->name)
@section('page-title', 'Achievement Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('achievements.index') }}" class="text-decoration-none text-muted">Achievement</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@push('styles')
<style>
.badge-card { border-radius:12px;padding:1rem;text-align:center;border:2px solid transparent; }
.badge-card.achieved { border-color:#22c55e; }
.badge-card.locked { opacity:.45;filter:grayscale(1); }
.badge-icon-wrap { width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .65rem;font-size:1.3rem; }
.progress { height:8px;border-radius:8px; }
.user-banner { border-radius:14px;padding:1.5rem 2rem;display:flex;align-items:center;gap:1rem;color:#fff; }
</style>
@endpush

@section('content')

<div class="row g-3 mb-4">
    <div class="col-12 col-md-7">
        @php $banner = $user->banner; @endphp
        @if($banner)
        <div class="user-banner" style="{{ $banner->gradient_style }}">
            <div style="font-size:2rem;opacity:.9"><i class="fa-solid {{ $banner->icon }}"></i></div>
            <div>
                <div style="font-size:.7rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Banner</div>
                <h4 class="fw-bold mb-0" style="color:{{ $banner->text_color }}">{{ $banner->label }}</h4>
                <p class="mb-0" style="font-size:.82rem;opacity:.85">{{ $user->name }}</p>
            </div>
        </div>
        @else
        <div class="user-banner" style="background:linear-gradient(135deg,#64748b,#334155)">
            <div style="font-size:2rem;opacity:.8"><i class="fa-solid fa-user"></i></div>
            <div>
                <div style="font-size:.7rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Anggota</div>
                <h4 class="fw-bold mb-0">{{ $user->name }}</h4>
            </div>
        </div>
        @endif
    </div>
    <div class="col-12 col-md-5">
        <div class="stat-card h-100">
            <p class="text-muted mb-2" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;font-weight:600">Statistik</p>
            <div class="d-flex gap-4">
                <div class="text-center">
                    <div class="stat-value text-primary">{{ $user->approved_devotions_count }}</div>
                    <div class="stat-label">Sharing Firman</div>
                </div>
                <div class="text-center">
                    <div class="stat-value text-warning">{{ $user->total_devotions_count }}</div>
                    <div class="stat-label">Renungan</div>
                </div>
                <div class="text-center">
                    <div class="stat-value text-success">{{ $myBadges->count() }}</div>
                    <div class="stat-label">Badge</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Progress --}}
<div class="row g-3 mb-4">
    @foreach($progress as $category => $data)
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    @if($category === 'sharing_firman')
                        <i class="fa-solid fa-cross text-primary me-2"></i>Sharing Firman
                    @else
                        <i class="fa-solid fa-book-open text-warning me-2"></i>Renungan
                    @endif
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:.82rem;color:#64748b">Total</span>
                    <strong>{{ $data['count'] }}</strong>
                </div>
                @foreach($data['steps'] as $step)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:.78rem;font-weight:{{ $step['achieved']?'600':'500' }};color:{{ $step['achieved']?'#22c55e':'#64748b' }}">
                            @if($step['achieved'])<i class="fa-solid fa-circle-check me-1"></i>@endif
                            {{ $step['label'] }}
                        </span>
                        <span style="font-size:.72rem;color:#94a3b8">{{ $data['count'] }}/{{ $step['required'] }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar {{ $step['achieved']?'bg-success':'bg-primary' }}"
                             style="width:{{ $step['percent'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Badges --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-trophy text-warning me-2"></i>Badge</h6>
    </div>
    <div class="card-body">
        @foreach(['sharing_firman'=>'Sharing Firman','renungan'=>'Renungan'] as $cat=>$catLabel)
        <p class="fw-semibold text-muted mb-3" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">{{ $catLabel }}</p>
        <div class="row g-2 mb-4">
            @foreach($allBadges->where('category',$cat) as $badge)
            @php $achieved = $myBadges->contains('id',$badge->id); @endphp
            <div class="col-6 col-sm-4 col-md-3">
                <div class="badge-card stat-card {{ $achieved?'achieved':'locked' }}">
                    <div class="badge-icon-wrap" style="background:{{ $badge->color }}22">
                        <i class="fa-solid {{ $badge->icon }}" style="color:{{ $badge->color }}"></i>
                    </div>
                    <div class="fw-semibold mb-1" style="font-size:.8rem">{{ $badge->badge_label }}</div>
                    <div class="text-muted" style="font-size:.7rem">{{ $badge->required_count }}x</div>
                    @if($achieved)
                    <span class="badge bg-success mt-1" style="font-size:.62rem"><i class="fa-solid fa-check me-1"></i>Diraih</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

{{-- Activity Log --}}
@if($activityLogs->isNotEmpty())
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-timeline text-primary me-2"></i>Riwayat Aktivitas</h6>
    </div>
    <div class="card-body p-0">
        @foreach($activityLogs as $log)
        <div class="d-flex gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }}">
            @php
                $logIcon = match(true) {
                    str_contains($log->type,'achievement') => 'fa-trophy text-warning',
                    str_contains($log->type,'approved')    => 'fa-check-circle text-success',
                    str_contains($log->type,'created')     => 'fa-pen text-primary',
                    default                                 => 'fa-circle-dot text-secondary',
                };
            @endphp
            <div class="flex-shrink-0 mt-1">
                <i class="fa-solid {{ $logIcon }}"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-0" style="font-size:.845rem">{{ $log->description }}</p>
                <p class="mb-0 text-muted" style="font-size:.74rem">{{ $log->created_at->diffForHumans() }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="mt-3">
    <a href="{{ route('achievements.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
    </a>
</div>
@endsection
