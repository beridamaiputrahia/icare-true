@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengumuman</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-bullhorn text-warning me-2"></i>Daftar Pengumuman</h6>
        @if($_feat['pengumuman'] ?? false)
        <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Pengumuman
        </a>
        @endif
    </div>
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group input-group-sm" style="max-width:280px">
                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request('search'))
            <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            @endif
        </form>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($announcements as $ann)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 border">
                    @if($ann->gambar)
                    <img src="{{ \App\Support\FileUrl::of($ann->gambar) }}" class="card-img-top" style="height:180px;object-fit:cover" alt="{{ $ann->judul }}">
                    @else
                    <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10" style="height:120px">
                        <i class="fa-solid fa-bullhorn fa-2x text-warning opacity-50"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title fw-semibold mb-1 text-truncate">{{ $ann->judul }}</h6>
                        <p class="card-text text-muted mb-2" style="font-size:.75rem">
                            <i class="fa-solid fa-user me-1"></i>{{ $ann->penulis }}
                            &nbsp;·&nbsp;{{ $ann->created_at->diffForHumans() }}
                        </p>
                        <p class="card-text text-muted small text-truncate-2" style="font-size:.82rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                            {{ strip_tags($ann->isi) }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent d-flex gap-1">
                        <a href="{{ route('announcements.show', $ann) }}" class="btn btn-sm btn-outline-info flex-grow-1">
                            <i class="fa-solid fa-eye me-1"></i>Baca
                        </a>
                        @if($_feat['pengumuman'] ?? false)
                        <a href="{{ route('announcements.edit', $ann) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form id="del-ann-{{ $ann->id }}" method="POST" action="{{ route('announcements.destroy', $ann) }}">
                            @csrf @method('DELETE')
                        </form>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-ann-{{ $ann->id }}" title="Hapus">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fa-solid fa-bullhorn fa-2x mb-2 d-block opacity-25"></i>
                <p class="mb-0">Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>
    </div>
    @if($announcements->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between">
        <small class="text-muted">{{ $announcements->total() }} pengumuman</small>
        {{ $announcements->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
