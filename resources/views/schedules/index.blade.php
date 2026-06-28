@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')
@section('page-title', 'Jadwal Kegiatan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Jadwal Kegiatan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Daftar Jadwal Kegiatan</h6>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('schedules.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Jadwal
        </a>
        @endif
    </div>
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group input-group-sm" style="max-width:280px">
                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari kegiatan, lokasi..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="max-width:140px">
                <option value="">Semua Status</option>
                <option value="upcoming" {{ request('status')=='upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing"  {{ request('status')=='ongoing'  ? 'selected' : '' }}>Ongoing</option>
                <option value="done"     {{ request('status')=='done'     ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('schedules.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kegiatan</th>
                    <th>Tanggal & Jam</th>
                    <th>Lokasi</th>
                    <th>Pembicara</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr>
                    <td class="text-muted" style="font-size:.8rem">{{ $schedules->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:.875rem">{{ $schedule->nama_kegiatan }}</div>
                    </td>
                    <td>
                        <div style="font-size:.8rem">{{ $schedule->formatted_date }}</div>
                        <div class="text-muted" style="font-size:.75rem"><i class="fa-regular fa-clock me-1"></i>{{ $schedule->formatted_time }}</div>
                    </td>
                    <td>
                        <div style="font-size:.8rem" class="text-truncate" style="max-width:140px">{{ $schedule->lokasi }}</div>
                        @if($schedule->link_maps)
                        <a href="{{ $schedule->link_maps }}" target="_blank" class="text-muted" style="font-size:.72rem">
                            <i class="fa-solid fa-map-location-dot me-1"></i>Maps
                        </a>
                        @endif
                    </td>
                    <td style="font-size:.8rem">{{ $schedule->pembicara ?? '-' }}</td>
                    <td><span class="badge badge-{{ $schedule->status }} text-capitalize">{{ $schedule->status }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form id="del-{{ $schedule->id }}" method="POST" action="{{ route('schedules.destroy', $schedule) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-{{ $schedule->id }}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="fa-regular fa-calendar-xmark fa-2x mb-2 d-block opacity-50"></i>
                        Belum ada jadwal kegiatan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($schedules->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between">
        <small class="text-muted">Menampilkan {{ $schedules->firstItem() }}–{{ $schedules->lastItem() }} dari {{ $schedules->total() }} data</small>
        {{ $schedules->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
