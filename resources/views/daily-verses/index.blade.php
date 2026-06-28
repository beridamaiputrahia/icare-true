@extends('layouts.app')

@section('title', 'Ayat Harian')
@section('page-title', 'Ayat Harian')
@section('breadcrumb')
    <li class="breadcrumb-item active">Ayat Harian</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-bible text-primary me-2"></i>Daftar Ayat Harian</h6>
        <a href="{{ route('daily-verses.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Ayat
        </a>
    </div>
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group input-group-sm" style="max-width:280px">
                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari referensi, ayat..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request('search'))
            <a href="{{ route('daily-verses.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Referensi</th>
                    <th>Ayat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($verses as $verse)
                <tr>
                    <td class="text-muted" style="font-size:.8rem">{{ $verses->firstItem() + $loop->index }}</td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary fw-semibold" style="font-size:.78rem">{{ $verse->referensi }}</span>
                    </td>
                    <td style="font-size:.82rem;max-width:300px">
                        <div class="text-truncate" style="max-width:280px" title="{{ $verse->ayat }}">{{ Str::limit($verse->ayat, 80) }}</div>
                    </td>
                    <td style="font-size:.82rem">
                        {{ $verse->tanggal ? $verse->tanggal->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td>
                        <span class="badge {{ $verse->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $verse->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('daily-verses.show', $verse) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('daily-verses.edit', $verse) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form id="del-v-{{ $verse->id }}" method="POST" action="{{ route('daily-verses.destroy', $verse) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-v-{{ $verse->id }}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-bible fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada ayat harian
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($verses->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between">
        <small class="text-muted">{{ $verses->total() }} ayat</small>
        {{ $verses->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
