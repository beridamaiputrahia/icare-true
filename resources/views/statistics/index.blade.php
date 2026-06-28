@extends('layouts.app')

@section('title', 'Statistik')
@section('page-title', 'Statistik Komunitas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Statistik</li>
@endsection

@section('page-actions')
<div class="d-flex gap-2">
    <a href="{{ route('export.schedules.pdf') }}" class="btn btn-sm btn-outline-secondary" target="_blank">
        <i class="fa-solid fa-calendar-days me-1"></i>Export Jadwal
    </a>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('export.statistics.pdf') }}" class="btn btn-sm btn-outline-danger" target="_blank">
        <i class="fa-solid fa-file-pdf me-1"></i>Export Statistik
    </a>
    @endif
</div>
@endsection

@push('styles')
<style>
.chart-wrap { position:relative;min-height:220px; }
.stat-mini { text-align:center;padding:.75rem 1rem; }
.stat-mini .num { font-size:1.6rem;font-weight:700;line-height:1.2;color:#1e293b; }
.stat-mini .lbl { font-size:.72rem;color:#94a3b8;margin-top:.2rem;text-transform:uppercase;letter-spacing:.04em; }
</style>
@endpush

@section('content')

{{-- Top KPI --}}
<div class="row g-3 mb-4">
    @php
        $kpis = [
            ['icon'=>'fa-users','color'=>'text-primary','bg'=>'bg-primary','val'=>$kpi['total_members'],'lbl'=>'Total Anggota'],
            ['icon'=>'fa-book-bible','color'=>'text-warning','bg'=>'bg-warning','val'=>$kpi['total_devotions'],'lbl'=>'Total Renungan'],
            ['icon'=>'fa-hands-praying','color'=>'text-info','bg'=>'bg-info','val'=>$kpi['total_prayers'],'lbl'=>'Total Doa'],
            ['icon'=>'fa-trophy','color'=>'text-success','bg'=>'bg-success','val'=>$kpi['total_achievements'],'lbl'=>'Badge Diberikan'],
        ];
    @endphp
    @foreach($kpis as $k)
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="rounded-3 p-3 {{ $k['bg'] }} bg-opacity-15 flex-shrink-0">
                <i class="fa-solid {{ $k['icon'] }} {{ $k['color'] }} fa-lg"></i>
            </div>
            <div>
                <div class="stat-value {{ $k['color'] }}">{{ $k['val'] }}</div>
                <div class="stat-label">{{ $k['lbl'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Row 1: Monthly Activity + Devotion Status --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-chart-bar text-primary me-2"></i>Aktivitas Bulanan (12 Bulan)</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartMonthly"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-circle-half-stroke text-warning me-2"></i>Status Renungan</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="chart-wrap w-100"><canvas id="chartDevotionStatus"></canvas></div>
                <div class="d-flex gap-3 mt-2 flex-wrap justify-content-center" style="font-size:.75rem">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#f59e0b;margin-right:4px"></span>Pending</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#3b82f6;margin-right:4px"></span>Disetujui</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#ef4444;margin-right:4px"></span>Ditolak</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Prayer Status + Member Growth --}}
<div class="row g-3 mb-3">
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-circle-half-stroke text-info me-2"></i>Status Doa</h6>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="chart-wrap w-100"><canvas id="chartPrayerStatus"></canvas></div>
                <div class="d-flex gap-3 mt-2 flex-wrap justify-content-center" style="font-size:.75rem">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#f59e0b;margin-right:4px"></span>Menunggu</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#3b82f6;margin-right:4px"></span>Aktif</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:2px;background:#22c55e;margin-right:4px"></span>Dijawab</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
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

{{-- Row 3: Top Writers + Age Group + Achievement Dist --}}
<div class="row g-3">
    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Penulis Renungan Terbanyak</h6>
            </div>
            <div class="card-body p-0">
                @forelse($topWriters as $i => $writer)
                <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }}">
                    <div class="fw-bold rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:32px;height:32px;font-size:.8rem">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fw-semibold text-truncate" style="font-size:.84rem">{{ $writer->name }}</p>
                        <div class="progress mt-1" style="height:4px">
                            <div class="progress-bar bg-primary" style="width:{{ $topWriters->max('devotions_count') > 0 ? ($writer->devotions_count / $topWriters->max('devotions_count') * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-primary rounded-pill">{{ $writer->devotions_count }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.85rem">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-layer-group text-info me-2"></i>Kelompok Usia</h6>
            </div>
            <div class="card-body">
                <div class="chart-wrap"><canvas id="chartAgeGroup"></canvas></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-trophy text-success me-2"></i>Badge Terpopuler</h6>
            </div>
            <div class="card-body p-0">
                @forelse($achievementStats as $stat)
                <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }}">
                    <div class="flex-shrink-0">
                        <i class="fa-solid {{ $stat['icon'] }}" style="color:{{ $stat['color'] }};font-size:1.1rem"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fw-semibold text-truncate" style="font-size:.8rem">{{ $stat['name'] }}</p>
                        <p class="mb-0 text-muted" style="font-size:.7rem">{{ $stat['category'] === 'sharing_firman' ? 'Sharing' : 'Renungan' }}</p>
                    </div>
                    <span class="badge bg-success rounded-pill flex-shrink-0">{{ $stat['count'] }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-muted" style="font-size:.85rem">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const chartDefaults = {
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, precision: 0 } }
    }
};

// Monthly Activity Bar Chart
new Chart(document.getElementById('chartMonthly'), {
    type: 'bar',
    data: {
        labels: @json($monthlyActivity['labels']),
        datasets: [
            {
                label: 'Renungan',
                data: @json($monthlyActivity['devotions']),
                backgroundColor: 'rgba(59,130,246,.7)',
                borderRadius: 4,
            },
            {
                label: 'Doa',
                data: @json($monthlyActivity['prayers']),
                backgroundColor: 'rgba(34,197,94,.7)',
                borderRadius: 4,
            }
        ]
    },
    options: {
        plugins: { legend: { display: true, position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: chartDefaults.scales,
        responsive: true, maintainAspectRatio: false
    }
});

// Devotion Status Donut
new Chart(document.getElementById('chartDevotionStatus'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Disetujui', 'Ditolak'],
        datasets: [{
            data: @json([$devotionStatus['pending'], $devotionStatus['approved'], $devotionStatus['rejected']]),
            backgroundColor: ['#f59e0b', '#3b82f6', '#ef4444'],
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '65%' }
});

// Prayer Status Donut
new Chart(document.getElementById('chartPrayerStatus'), {
    type: 'doughnut',
    data: {
        labels: ['Menunggu', 'Aktif', 'Dijawab'],
        datasets: [{
            data: @json([$prayerStatus['pending'], $prayerStatus['approved'], $prayerStatus['answered']]),
            backgroundColor: ['#f59e0b', '#3b82f6', '#22c55e'],
            borderWidth: 2, borderColor: '#fff'
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '65%' }
});

// Member Growth Line
new Chart(document.getElementById('chartMemberGrowth'), {
    type: 'line',
    data: {
        labels: @json($memberGrowth['labels']),
        datasets: [{
            label: 'Anggota Baru',
            data: @json($memberGrowth['data']),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34,197,94,.1)',
            fill: true,
            tension: .4,
            pointRadius: 4, pointBackgroundColor: '#22c55e'
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: chartDefaults.scales,
        responsive: true, maintainAspectRatio: false
    }
});

// Age Group Bar
new Chart(document.getElementById('chartAgeGroup'), {
    type: 'bar',
    data: {
        labels: @json(array_keys($ageGroups)),
        datasets: [{
            data: @json(array_values($ageGroups)),
            backgroundColor: ['#3b82f6','#22c55e','#f59e0b','#8b5cf6','#ef4444'],
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0, font: { size: 10 } } },
            y: { grid: { display: false }, ticks: { font: { size: 10 } } }
        },
        responsive: true, maintainAspectRatio: false
    }
});
</script>
@endpush
