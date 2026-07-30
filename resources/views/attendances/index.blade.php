@extends('layouts.app')

@section('title', 'Kelola Kehadiran — ' . $schedule->nama_kegiatan)
@section('page-title', 'Kelola Kehadiran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}" class="text-decoration-none text-muted">Jadwal</a></li>
    <li class="breadcrumb-item"><a href="{{ route('schedules.show', $schedule) }}" class="text-decoration-none text-muted">{{ $schedule->nama_kegiatan }}</a></li>
    <li class="breadcrumb-item active">Kelola Kehadiran</li>
@endsection

@section('content')
<div class="row justify-content-center g-3">
    <div class="col-12 col-lg-9">

        {{-- Sudah Hadir --}}
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-clipboard-check text-success me-2"></i>Sudah Hadir ({{ $schedule->attendances->count() }})</h6>
                <a href="{{ route('attendances.scan', $schedule) }}" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-qrcode me-1"></i>Scan Absensi
                </a>
            </div>
            <div class="card-body">
                @if($schedule->attendances->isEmpty())
                    <p class="text-muted small mb-0">Belum ada anggota yang absen.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Waktu Hadir</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedule->attendances->sortBy(fn($a) => $a->member->nama_lengkap) as $att)
                                <tr>
                                    <td>{{ $att->member->nama_lengkap }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('attendances.update', [$schedule, $att]) }}" class="d-flex align-items-center gap-2">
                                            @csrf @method('PUT')
                                            <input type="datetime-local" name="scanned_at" class="form-control form-control-sm"
                                                   style="width:auto"
                                                   value="{{ $att->scanned_at->format('Y-m-d\TH:i') }}">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Simpan waktu">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <form id="del-att-{{ $att->id }}" method="POST" action="{{ route('attendances.destroy', [$schedule, $att]) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                        </form>
                                        <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-att-{{ $att->id }}" title="Hapus kehadiran">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Absen Manual --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user-plus text-primary me-2"></i>Absen Manual (Belum Hadir)</h6>
                <small class="text-muted">Centang anggota yang hadir tapi tidak/belum sempat scan QR.</small>
            </div>
            <div class="card-body">
                @if($members->isEmpty())
                    <p class="text-muted small mb-0">Semua anggota aktif sudah tercatat hadir.</p>
                @else
                    <form method="POST" action="{{ route('attendances.store-manual', $schedule) }}">
                        @csrf
                        <div class="mb-2">
                            <input type="text" id="member-search" class="form-control form-control-sm" placeholder="Cari nama anggota...">
                        </div>
                        <div class="row g-2 mb-3" id="member-checklist" style="max-height:320px;overflow-y:auto">
                            @foreach($members as $member)
                            <div class="col-md-6 member-row" data-name="{{ Str::lower($member->nama_lengkap) }}">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="member_ids[]" value="{{ $member->id }}" id="member-{{ $member->id }}">
                                    <label class="form-check-label small" for="member-{{ $member->id }}">{{ $member->nama_lengkap }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-check me-1"></i>Catat Kehadiran Terpilih
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Detail Jadwal
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('member-search')?.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    document.querySelectorAll('.member-row').forEach(row => {
        row.style.display = row.dataset.name.includes(q) ? '' : 'none';
    });
});
</script>
@endpush
