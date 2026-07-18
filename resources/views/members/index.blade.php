@extends('layouts.app')

@section('title', 'Data Anggota')
@section('page-title', 'Data Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item active">Data Anggota</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-users text-primary me-2"></i>Daftar Anggota Komunitas</h6>
        @if($_feat['anggota'] ?? false)
        <a href="{{ route('members.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-user-plus me-1"></i>Tambah Anggota
        </a>
        @endif
    </div>
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <div class="input-group input-group-sm" style="max-width:280px">
                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama, nomor covenant..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="max-width:130px">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Anggota</th>
                    <th>Covenant No.</th>
                    <th>Nomor HP</th>
                    <th>Tgl Lahir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td class="text-muted" style="font-size:.8rem">{{ $members->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($member->foto)
                                <img src="{{ \App\Support\FileUrl::of($member->foto) }}" class="member-photo" alt="{{ $member->nama_lengkap }}">
                            @else
                                <div class="avatar-placeholder" style="width:40px;height:40px;font-size:.8rem;background:#e2e8f0">
                                    {{ strtoupper(substr($member->nama_lengkap, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $member->nama_lengkap }}</div>
                                @if($member->nama_panggilan)
                                <div class="text-muted" style="font-size:.75rem">"{{ $member->nama_panggilan }}"</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.82rem">{{ $member->covenant_number ?? '-' }}</td>
                    <td style="font-size:.82rem">
                        @if($member->nomor_hp)
                        <a href="tel:{{ $member->nomor_hp }}" class="text-decoration-none text-dark">{{ $member->nomor_hp }}</a>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="font-size:.82rem">
                        @if($member->tanggal_lahir)
                        {{ $member->tanggal_lahir->translatedFormat('d M Y') }}
                        @if($member->birthday_this_month)
                        <span class="ms-1" title="Ulang tahun bulan ini">🎂</span>
                        @endif
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $member->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($_feat['anggota'] ?? false)
                            <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form id="del-m-{{ $member->id }}" method="POST" action="{{ route('members.destroy', $member) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-m-{{ $member->id }}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-users-slash fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada data anggota
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between">
        <small class="text-muted">Menampilkan {{ $members->firstItem() }}–{{ $members->lastItem() }} dari {{ $members->total() }} anggota</small>
        {{ $members->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
