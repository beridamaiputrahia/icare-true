@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-primary bg-opacity-10">
                    <i class="fa-solid fa-users text-primary"></i>
                </div>
                <span class="badge badge-upcoming">Aktif</span>
            </div>
            <div class="stat-value">{{ $totalAnggota }}</div>
            <div class="stat-label">Total Anggota</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-success bg-opacity-10">
                    <i class="fa-solid fa-calendar-days text-success"></i>
                </div>
                <span class="badge badge-approved">Total</span>
            </div>
            <div class="stat-value">{{ $totalJadwal }}</div>
            <div class="stat-label">Total Jadwal</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-warning bg-opacity-10">
                    <i class="fa-solid fa-book-open-reader text-warning"></i>
                </div>
                <span class="badge badge-approved">Disetujui</span>
            </div>
            <div class="stat-value">{{ $totalRenungan }}</div>
            <div class="stat-label">Total Renungan</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="stat-icon bg-danger bg-opacity-10">
                    <i class="fa-solid fa-bullhorn text-danger"></i>
                </div>
                <span class="badge badge-approved">Aktif</span>
            </div>
            <div class="stat-value">{{ $totalPengumuman }}</div>
            <div class="stat-label">Total Pengumuman</div>
        </div>
    </div>
</div>

{{-- Ayat Harian + Countdown --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-7">
        @if($ayatHarian)
        <div class="verse-card h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-bible" style="opacity:.7"></i>
                <span style="font-size:.72rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Ayat Harian — {{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <p class="verse-text mb-3">"{{ $ayatHarian->ayat }}"</p>
            <p class="verse-ref mb-0">— {{ $ayatHarian->referensi }}</p>
            @if($ayatHarian->renungan_singkat)
                <hr style="border-color:rgba(255,255,255,.15);margin:1rem 0">
                <p style="font-size:.82rem;opacity:.85;line-height:1.7;margin:0">{{ $ayatHarian->renungan_singkat }}</p>
            @endif
        </div>
        @else
        <div class="card h-100">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa-solid fa-bible fa-2x mb-2 d-block opacity-25"></i>
                <p class="mb-2 small">Belum ada ayat harian</p>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('daily-verses.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus me-1"></i>Tambah Ayat
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-12 col-lg-5">
        @if($jadwalTerdekat)
        <div class="countdown-box h-100">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-stopwatch" style="opacity:.7"></i>
                <span style="font-size:.72rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Jadwal Terdekat</span>
            </div>
            <p class="fw-bold mb-1" style="font-size:.95rem">{{ $jadwalTerdekat->nama_kegiatan }}</p>
            <p class="mb-3" style="font-size:.8rem;opacity:.85">
                <i class="fa-solid fa-location-dot me-1"></i>{{ $jadwalTerdekat->lokasi }}
            </p>
            <div class="row g-2 text-center mb-3">
                <div class="col-3">
                    <div class="count-number" id="cnt-days">--</div>
                    <div class="count-label">Hari</div>
                </div>
                <div class="col-3">
                    <div class="count-number" id="cnt-hours">--</div>
                    <div class="count-label">Jam</div>
                </div>
                <div class="col-3">
                    <div class="count-number" id="cnt-mins">--</div>
                    <div class="count-label">Menit</div>
                </div>
                <div class="col-3">
                    <div class="count-number" id="cnt-secs">--</div>
                    <div class="count-label">Detik</div>
                </div>
            </div>
            <p class="mb-0" style="font-size:.78rem;opacity:.8">
                <i class="fa-regular fa-calendar me-1"></i>{{ $jadwalTerdekat->tanggal->translatedFormat('l, d F Y') }}
                &nbsp;&middot;&nbsp;
                <i class="fa-regular fa-clock me-1"></i>{{ $jadwalTerdekat->formatted_time }}
            </p>
        </div>
        @else
        <div class="countdown-box h-100 d-flex flex-column align-items-center justify-content-center text-center py-4">
            <i class="fa-solid fa-calendar-xmark fa-2x mb-2" style="opacity:.5"></i>
            <p class="mb-0" style="font-size:.85rem;opacity:.8">Tidak ada jadwal mendatang</p>
        </div>
        @endif
    </div>
</div>

{{-- Leaderboard Widget ─────────────────────────────────────── --}}
@php
    $topWeekly = app(\App\Services\LeaderboardService::class)->weekly(3);
@endphp
@if($topWeekly->isNotEmpty())
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-ranking-star text-warning me-2"></i>Top Anggota Minggu Ini</h6>
                <a href="{{ route('leaderboard.index') }}" class="btn btn-sm btn-outline-warning">Leaderboard</a>
            </div>
            <div class="card-body p-0">
                @foreach($topWeekly as $entry)
                @php $u = $entry['user']; @endphp
                @if($u)
                <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }}">
                    <div class="fw-bold text-center flex-shrink-0"
                         style="width:28px;font-size:1.1rem">
                        @if($entry['rank']===1) 🥇
                        @elseif($entry['rank']===2) 🥈
                        @else 🥉
                        @endif
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:var(--app-primary,#2563eb);color:#fff;font-weight:700;font-size:.85rem">
                        {{ strtoupper(substr($u->name,0,1)) }}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fw-semibold text-truncate" style="font-size:.875rem">{{ $u->name }}</p>
                        <div style="font-size:.7rem;color:#94a3b8">{{ $entry['level_name'] }}</div>
                    </div>
                    <div class="fw-bold text-primary" style="font-size:.9rem">{{ number_format($entry['points']) }}<span style="font-size:.65rem;color:#94a3b8;font-weight:400"> pts</span></div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-coins text-warning me-2"></i>Poin Saya</h6>
                <a href="{{ route('points.index') }}" class="btn btn-sm btn-outline-secondary">Riwayat</a>
            </div>
            <div class="card-body">
                @php $me = auth()->user(); $progress = $me->level_progress; @endphp
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:{{ $progress['current_color'] }}20">
                        <i class="fa-solid {{ $progress['current_icon'] }} fa-lg" style="color:{{ $progress['current_color'] }}"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:{{ $progress['current_color'] }}">{{ $progress['current_name'] }}</div>
                        <div style="font-size:.8rem;color:#64748b">Level {{ $progress['current_level'] }} &bull; {{ number_format($me->total_points) }} poin</div>
                    </div>
                </div>
                @if(!$progress['is_max'])
                <div class="d-flex justify-content-between mb-1" style="font-size:.72rem;color:#94a3b8">
                    <span>Menuju {{ $progress['next_name'] }}</span>
                    <span>{{ $progress['points_to_next'] }} lagi</span>
                </div>
                <div class="progress" style="height:8px">
                    <div class="progress-bar" style="width:{{ $progress['percent'] }}%;background:{{ $progress['current_color'] }};border-radius:10px"></div>
                </div>
                @else
                <div class="alert alert-success border-0 mb-0 py-2" style="font-size:.8rem">
                    <i class="fa-solid fa-star me-1"></i>Level Tertinggi!
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- Jadwal + Chart + Pengumuman --}}
<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Jadwal Mendatang</h6>
                <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingSchedules as $schedule)
                <div class="d-flex align-items-center gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="text-center flex-shrink-0" style="width:44px">
                        <div class="fw-bold text-primary" style="font-size:1.2rem;line-height:1">{{ $schedule->tanggal->format('d') }}</div>
                        <div class="text-muted" style="font-size:.65rem;text-transform:uppercase">{{ $schedule->tanggal->format('M') }}</div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="mb-0 fw-semibold text-truncate" style="font-size:.875rem">{{ $schedule->nama_kegiatan }}</p>
                        <p class="mb-0 text-muted text-truncate" style="font-size:.75rem">
                            <i class="fa-solid fa-location-dot me-1"></i>{{ $schedule->lokasi }}
                            &nbsp;&middot;&nbsp;
                            <i class="fa-regular fa-clock me-1"></i>{{ $schedule->formatted_time }}
                        </p>
                    </div>
                    <span class="badge badge-{{ $schedule->status }} flex-shrink-0 text-capitalize">{{ $schedule->status }}</span>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-calendar-xmark fa-xl mb-2 d-block opacity-50"></i>
                    <small>Tidak ada jadwal mendatang</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-chart-bar text-success me-2"></i>Renungan per Bulan</h6>
            </div>
            <div class="card-body" style="padding:.75rem 1rem">
                <canvas id="devotionChart" height="120"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-bullhorn text-warning me-2"></i>Pengumuman Terbaru</h6>
                <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentAnnouncements as $ann)
                <a href="{{ route('announcements.show', $ann) }}" class="d-flex gap-3 px-3 py-2 text-decoration-none {{ !$loop->last ? 'border-bottom' : '' }}"
                   style="transition:background .15s" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <div class="flex-shrink-0 mt-1 rounded-2 d-flex align-items-center justify-content-center" style="width:32px;height:32px;background:#fef3c7;color:#d97706">
                        <i class="fa-solid fa-bullhorn fa-xs"></i>
                    </div>
                    <div class="overflow-hidden">
                        <p class="mb-0 fw-semibold text-dark text-truncate" style="font-size:.82rem">{{ $ann->judul }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem">{{ $ann->penulis }} · {{ $ann->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @empty
                <div class="text-center py-3 text-muted"><small>Belum ada pengumuman</small></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
@if($jadwalTerdekat)
const targetDate = new Date('{{ $jadwalTerdekat->tanggal->format('Y-m-d') }}T{{ $jadwalTerdekat->jam }}');
function updateCountdown() {
    const diff = targetDate - new Date();
    if (diff <= 0) { ['days','hours','mins','secs'].forEach(k => document.getElementById('cnt-'+k).textContent='00'); return; }
    document.getElementById('cnt-days').textContent  = String(Math.floor(diff/86400000)).padStart(2,'0');
    document.getElementById('cnt-hours').textContent = String(Math.floor((diff%86400000)/3600000)).padStart(2,'0');
    document.getElementById('cnt-mins').textContent  = String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
    document.getElementById('cnt-secs').textContent  = String(Math.floor((diff%60000)/1000)).padStart(2,'0');
}
updateCountdown(); setInterval(updateCountdown, 1000);
@endif

const ctx = document.getElementById('devotionChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($monthlyStats, 'label')) !!},
            datasets: [{ label: 'Renungan', data: {!! json_encode(array_column($monthlyStats, 'devotion')) !!},
                backgroundColor: 'rgba(37,99,235,.15)', borderColor: 'rgba(37,99,235,.8)',
                borderWidth: 1.5, borderRadius: 4 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: '#f1f5f9' } },
                x: { ticks: { font: { size: 10 } }, grid: { display: false } }
            }
        }
    });
}
</script>
@endpush
