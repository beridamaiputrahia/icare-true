@extends('layouts.app')

@section('title', 'Community Analytics')
@section('page-title', 'Dashboard Analytics Komunitas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Analytics</li>
@endsection

@push('styles')
<style>
.chart-wrap { position: relative; min-height: 240px; }
.kpi-card {
    background: #fff; border-radius: 14px; padding: 1.25rem;
    border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,.08); height: 100%;
}
.kpi-val { font-size: 2rem; font-weight: 800; line-height: 1; }
.kpi-lbl { font-size: .75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-top: .25rem; }
.top-member-row { display: flex; align-items: center; gap: .75rem; padding: .65rem 0; border-bottom: 1px solid #f1f5f9; }
.top-member-row:last-child { border-bottom: none; }
.top-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--app-primary,#2563eb); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; flex-shrink: 0; }
</style>
@endpush

@section('content')

{{-- KPI ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    @php
    $kpiItems = [
        ['icon'=>'fa-users',       'color'=>'primary',  'val'=>$kpi['total_members'],    'lbl'=>'Total Anggota'],
        ['icon'=>'fa-user-check',  'color'=>'success',  'val'=>$kpi['active_members'],   'lbl'=>'Aktif 30 Hari'],
        ['icon'=>'fa-book-open-reader','color'=>'warning','val'=>$kpi['total_devotions'],'lbl'=>'Renungan Disetujui'],
        ['icon'=>'fa-hands-praying','color'=>'info',    'val'=>$kpi['total_prayers'],    'lbl'=>'Total Doa'],
        ['icon'=>'fa-trophy',      'color'=>'success',  'val'=>$kpi['total_achievements'],'lbl'=>'Badge Diberikan'],
        ['icon'=>'fa-coins',       'color'=>'warning',  'val'=>number_format($kpi['total_points']),'lbl'=>'Total Poin Komunitas'],
        ['icon'=>'fa-share-from-square','color'=>'primary','val'=>$kpi['total_sharing'],'lbl'=>'Sharing Firman'],
        ['icon'=>'fa-images',      'color'=>'info',     'val'=>$kpi['total_albums'],     'lbl'=>'Album Galeri'],
    ];
    @endphp
    @foreach($kpiItems as $item)
    <div class="col-6 col-md-3">
        <div class="kpi-card d-flex align-items-center gap-3">
            <div class="rounded-3 p-3 bg-{{ $item['color'] }} bg-opacity-10 flex-shrink-0">
                <i class="fa-solid {{ $item['icon'] }} text-{{ $item['color'] }} fa-lg"></i>
            </div>
            <div>
                <div class="kpi-val text-{{ $item['color'] }}">{{ $item['val'] }}</div>
                <div class="kpi-lbl">{{ $item['lbl'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Row 1: Monthly Activity + Member Growth ──────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Aktivitas Bulanan (12 Bulan)</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartMonthlyActivity"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-chart-line text-success me-2"></i>Pertumbuhan Anggota</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartMemberGrowth"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Level Dist + Point Type + Achievement Dist ───────── --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-layer-group text-info me-2"></i>Distribusi Level Anggota</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartLevelDist"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-coins text-warning me-2"></i>Distribusi Perolehan Poin</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartPointType"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-trophy text-success me-2"></i>Badge Terpopuler</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartAchievementDist"></canvas></div>
            </div>
        </div>
    </div>
</div>

{{-- Row 3: Top Members ───────────────────────────────────────── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-ranking-star text-warning me-2"></i>Anggota Teraktif (All-Time)</h6>
        <a href="{{ route('leaderboard.index') }}" class="btn btn-sm btn-outline-primary">Leaderboard Lengkap</a>
    </div>
    <div class="card-body p-0">
        @forelse($topMembers as $i => $member)
        <div class="top-member-row px-4">
            <div class="fw-bold text-muted" style="width:28px;font-size:.85rem">{{ $i+1 }}</div>
            <div class="top-avatar">{{ strtoupper(substr($member->name,0,1)) }}</div>
            <div class="flex-grow-1 overflow-hidden">
                <p class="mb-0 fw-semibold text-truncate" style="font-size:.85rem">{{ $member->name }}</p>
                <div style="font-size:.72rem;color:#94a3b8">Level {{ $member->level }} — {{ $member->level_name }}</div>
            </div>
            <div class="text-end flex-shrink-0">
                <div class="fw-bold text-primary" style="font-size:.9rem">{{ number_format($member->total_points) }}</div>
                <div style="font-size:.7rem;color:#94a3b8">poin</div>
            </div>
        </div>
        @empty
        <div class="text-center py-4 text-muted small">Belum ada data aktivitas</div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script>
const chartColors = ['#3b82f6','#22c55e','#f59e0b','#8b5cf6','#ef4444','#06b6d4','#ec4899','#10b981'];

// Monthly Activity
new Chart(document.getElementById('chartMonthlyActivity'), {
    type: 'bar',
    data: {
        labels: @json($monthlyActivity['labels']),
        datasets: [
            { label:'Renungan', data:@json($monthlyActivity['devotions']), backgroundColor:'rgba(59,130,246,.7)', borderRadius:4 },
            { label:'Doa',      data:@json($monthlyActivity['prayers']),  backgroundColor:'rgba(34,197,94,.7)',  borderRadius:4 },
            { label:'Poin (/10)',data:@json(array_map(fn($v)=>round($v/10),$monthlyActivity['points'])), backgroundColor:'rgba(245,158,11,.7)', borderRadius:4 },
        ]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:true,position:'top',labels:{boxWidth:12,font:{size:11}} } }, scales:{ x:{grid:{display:false},ticks:{font:{size:10}}}, y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{font:{size:10},precision:0}} } }
});

// Member Growth
new Chart(document.getElementById('chartMemberGrowth'), {
    type: 'line',
    data: {
        labels: @json($memberGrowth['labels']),
        datasets: [{ data:@json($memberGrowth['data']), borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,.1)', fill:true, tension:.4, pointRadius:3, pointBackgroundColor:'#22c55e' }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ x:{grid:{display:false},ticks:{font:{size:10}}}, y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{font:{size:10},precision:0}} } }
});

// Level Distribution
new Chart(document.getElementById('chartLevelDist'), {
    type: 'doughnut',
    data: {
        labels: @json($levelDist->pluck('label')),
        datasets: [{ data:@json($levelDist->pluck('count')), backgroundColor:@json($levelDist->pluck('color')), borderWidth:2, borderColor:'#fff' }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom',labels:{boxWidth:12,font:{size:10}} } }, cutout:'55%' }
});

// Point Type Distribution
new Chart(document.getElementById('chartPointType'), {
    type: 'doughnut',
    data: {
        labels: @json($pointTypeDist->pluck('label')),
        datasets: [{ data:@json($pointTypeDist->pluck('total')), backgroundColor:chartColors, borderWidth:2, borderColor:'#fff' }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom',labels:{boxWidth:12,font:{size:10}} } }, cutout:'55%' }
});

// Achievement Distribution
new Chart(document.getElementById('chartAchievementDist'), {
    type: 'bar',
    data: {
        labels: @json($achievementDist->pluck('name')),
        datasets: [{ data:@json($achievementDist->pluck('count')), backgroundColor:chartColors, borderRadius:6 }]
    },
    options: { indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{ x:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{precision:0,font:{size:10}}}, y:{grid:{display:false},ticks:{font:{size:10}}} } }
});
</script>
@endpush
