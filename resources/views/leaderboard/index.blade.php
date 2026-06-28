@extends('layouts.app')

@section('title', 'Leaderboard')
@section('page-title', 'Leaderboard Aktivitas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Leaderboard</li>
@endsection

@push('styles')
<style>
.lb-card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,.08); overflow:hidden; }
.lb-header { padding:.85rem 1.25rem; border-bottom:1px solid #e2e8f0; background:#f8fafc; }
.lb-row { display:flex; align-items:center; gap:.85rem; padding:.75rem 1.25rem; transition:background .15s; border-bottom:1px solid #f1f5f9; }
.lb-row:last-child { border-bottom:none; }
.lb-row:hover { background:#f8fafc; }
.rank-badge {
    width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:.85rem; flex-shrink:0;
}
.rank-1 { background:linear-gradient(135deg,#fbbf24,#f59e0b); color:#fff; box-shadow:0 2px 8px rgba(251,191,36,.4); }
.rank-2 { background:linear-gradient(135deg,#94a3b8,#64748b); color:#fff; }
.rank-3 { background:linear-gradient(135deg,#cd7c2e,#b45309); color:#fff; }
.rank-other { background:#f1f5f9; color:#475569; }
.lb-avatar {
    width:40px; height:40px; border-radius:50%; flex-shrink:0; overflow:hidden;
    background:var(--app-primary,#2563eb); color:#fff; font-weight:700;
    display:flex; align-items:center; justify-content:center; font-size:.9rem;
}
.lb-avatar img { width:100%; height:100%; object-fit:cover; }
.lb-name { font-weight:600; font-size:.875rem; color:#1e293b; margin:0; line-height:1.2; }
.lb-sub  { font-size:.72rem; color:#94a3b8; }
.lb-pts  { margin-left:auto; text-align:right; flex-shrink:0; }
.lb-pts .pts-val { font-weight:700; font-size:1rem; color:var(--app-primary,#2563eb); line-height:1; }
.lb-pts .pts-lbl { font-size:.65rem; color:#94a3b8; text-transform:uppercase; letter-spacing:.05em; }
.my-rank-card {
    background:linear-gradient(135deg,var(--app-primary,#2563eb),#7c3aed);
    border-radius:14px; padding:1.25rem 1.5rem; color:#fff;
}
.trophy-icon { font-size:2.5rem; }
.tab-btn { border:none; background:none; padding:.5rem 1rem; border-radius:8px; font-size:.825rem; font-weight:500; color:#64748b; cursor:pointer; transition:all .2s; }
.tab-btn.active { background:var(--app-primary,#2563eb); color:#fff; }
.level-tag { display:inline-flex; align-items:center; gap:4px; font-size:.68rem; padding:.15rem .5rem; border-radius:8px; }
.empty-lb { text-align:center; padding:2.5rem 1rem; color:#94a3b8; }
</style>
@endpush

@section('content')

{{-- My Rank Card ─────────────────────────────────────────────── --}}
<div class="my-rank-card mb-4">
    <div class="row align-items-center g-3">
        <div class="col-auto">
            <div class="trophy-icon">🏆</div>
        </div>
        <div class="col">
            <p class="fw-bold mb-1" style="font-size:1.05rem">Peringkat Kamu</p>
            <div class="d-flex gap-3 flex-wrap">
                <div>
                    <div style="font-size:1.6rem;font-weight:800;line-height:1">
                        {{ $myRanks['weekly'] === 0 ? '—' : '#' . $myRanks['weekly'] }}
                    </div>
                    <div style="font-size:.68rem;opacity:.8;text-transform:uppercase">Mingguan</div>
                </div>
                <div>
                    <div style="font-size:1.6rem;font-weight:800;line-height:1">
                        {{ $myRanks['monthly'] === 0 ? '—' : '#' . $myRanks['monthly'] }}
                    </div>
                    <div style="font-size:.68rem;opacity:.8;text-transform:uppercase">Bulanan</div>
                </div>
                <div>
                    <div style="font-size:1.6rem;font-weight:800;line-height:1">#{{ $myRanks['all_time'] }}</div>
                    <div style="font-size:.68rem;opacity:.8;text-transform:uppercase">All-Time</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto text-md-end">
            <div style="font-size:.78rem;opacity:.8">Total Poin Kamu</div>
            <div style="font-size:2rem;font-weight:800;line-height:1">{{ number_format(auth()->user()->total_points) }}</div>
            <div style="font-size:.72rem;opacity:.75">
                <i class="fa-solid fa-seedling me-1"></i>{{ auth()->user()->level_name }}
            </div>
        </div>
    </div>
</div>

{{-- Tabs ─────────────────────────────────────────────────────── --}}
<div class="d-flex gap-1 mb-3 flex-wrap">
    <button class="tab-btn active" data-tab="weekly">Mingguan</button>
    <button class="tab-btn" data-tab="monthly">Bulanan</button>
    <button class="tab-btn" data-tab="yearly">Tahunan</button>
    <button class="tab-btn" data-tab="alltime">All-Time</button>
</div>

{{-- Leaderboard Panels ───────────────────────────────────────── --}}
<div class="lb-card">
    @foreach(['weekly' => $weekly, 'monthly' => $monthly, 'yearly' => $yearly, 'alltime' => $allTime] as $tab => $entries)
    <div id="tab-{{ $tab }}" style="{{ $tab !== 'weekly' ? 'display:none' : '' }}">
        @forelse($entries as $entry)
        @php $u = $entry['user']; @endphp
        @if(!$u) @continue @endif
        <div class="lb-row {{ $u->id === auth()->id() ? 'bg-primary bg-opacity-5' : '' }}">
            {{-- Rank --}}
            <div class="rank-badge {{ $entry['rank'] <= 3 ? 'rank-'.$entry['rank'] : 'rank-other' }}">
                @if($entry['rank'] === 1) 🥇
                @elseif($entry['rank'] === 2) 🥈
                @elseif($entry['rank'] === 3) 🥉
                @else {{ $entry['rank'] }}
                @endif
            </div>

            {{-- Avatar --}}
            <div class="lb-avatar">
                @if($u->avatar_url)
                <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}">
                @else
                {{ strtoupper(substr($u->name, 0, 1)) }}
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-grow-1 overflow-hidden">
                <p class="lb-name text-truncate">
                    {{ $u->name }}
                    @if($u->id === auth()->id())
                    <span class="badge bg-primary ms-1" style="font-size:.6rem">Kamu</span>
                    @endif
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    {{-- Level --}}
                    <span class="level-tag" style="background:{{ $entry['level_color'] }}20;color:{{ $entry['level_color'] }}">
                        <i class="fa-solid {{ $entry['level_icon'] }}" style="font-size:.6rem"></i>
                        {{ $entry['level_name'] }}
                    </span>
                    {{-- Highest Achievement --}}
                    @php $topAchieve = $u->achievements()->orderByDesc('required_count')->first(); @endphp
                    @if($topAchieve)
                    <span class="level-tag" style="background:{{ $topAchieve->color }}20;color:{{ $topAchieve->color }}">
                        <i class="fa-solid {{ $topAchieve->icon }}" style="font-size:.6rem"></i>
                        {{ $topAchieve->badge_label }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Points --}}
            <div class="lb-pts">
                <div class="pts-val">{{ number_format($entry['points']) }}</div>
                <div class="pts-lbl">poin</div>
            </div>
        </div>
        @empty
        <div class="empty-lb">
            <i class="fa-solid fa-ranking-star fa-2x mb-2 d-block opacity-25"></i>
            <p class="mb-0 small">Belum ada aktivitas dalam periode ini</p>
        </div>
        @endforelse
    </div>
    @endforeach
</div>

{{-- Level Legend ─────────────────────────────────────────────── --}}
<div class="card mt-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-layer-group text-primary me-2"></i>Sistem Level Komunitas</h6>
    </div>
    <div class="card-body">
        <div class="row g-2">
            @foreach($levels as $lvl => $data)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="text-center p-3 rounded-3" style="background:{{ $data['color'] }}15;border:1px solid {{ $data['color'] }}30">
                    <i class="fa-solid {{ $data['icon'] }} fa-lg mb-2 d-block" style="color:{{ $data['color'] }}"></i>
                    <div style="font-size:.75rem;font-weight:700;color:{{ $data['color'] }}">Level {{ $lvl }}</div>
                    <div style="font-size:.7rem;color:#64748b">{{ $data['name'] }}</div>
                    <div style="font-size:.65rem;color:#94a3b8">{{ number_format($data['min']) }}+ poin</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const tab = this.dataset.tab;
        document.querySelectorAll('[id^="tab-"]').forEach(p => p.style.display = 'none');
        document.getElementById('tab-' + tab).style.display = 'block';
    });
});
</script>
@endpush
