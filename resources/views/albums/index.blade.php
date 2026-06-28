@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Galeri</li>
@endsection

@section('page-actions')
@if(auth()->user()->isAdmin())
<a href="{{ route('albums.create') }}" class="btn btn-sm btn-primary">
    <i class="fa-solid fa-plus me-1"></i>Buat Album
</a>
@endif
@endsection

@push('styles')
<style>
.album-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:1rem; }
.album-card {
    border-radius:14px; overflow:hidden; background:#fff;
    border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,.08);
    transition:transform .2s,box-shadow .2s; text-decoration:none; color:inherit; display:block;
}
.album-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.12); color:inherit; }
.album-cover { height:180px; background:#e2e8f0; position:relative; overflow:hidden; }
.album-cover img { width:100%; height:100%; object-fit:cover; transition:transform .3s; }
.album-card:hover .album-cover img { transform:scale(1.05); }
.cover-placeholder { display:flex; align-items:center; justify-content:center; height:100%; background:linear-gradient(135deg,#e2e8f0,#cbd5e1); }
.photo-count { position:absolute; bottom:8px; right:8px; background:rgba(0,0,0,.6); color:#fff; border-radius:20px; padding:.2rem .65rem; font-size:.72rem; font-weight:600; }
.album-info { padding:.85rem 1rem; }
.album-title { font-size:.9rem; font-weight:700; color:#1e293b; margin:0 0 .2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.album-date { font-size:.72rem; color:#94a3b8; }
</style>
@endpush

@section('content')

@if($albums->isEmpty())
<div class="text-center py-5 text-muted">
    <i class="fa-solid fa-images fa-3x mb-3 d-block opacity-25"></i>
    <h5 class="fw-semibold">Belum Ada Album</h5>
    <p class="small">Belum ada galeri kegiatan yang dibagikan.</p>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('albums.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i>Buat Album Pertama
    </a>
    @endif
</div>
@else
<div class="album-grid">
    @foreach($albums as $album)
    <a href="{{ route('albums.show', $album) }}" class="album-card">
        <div class="album-cover">
            @if($album->cover_url)
            <img src="{{ $album->cover_url }}" alt="{{ $album->judul }}" loading="lazy">
            @else
            <div class="cover-placeholder">
                <i class="fa-solid fa-images fa-2x" style="color:#94a3b8"></i>
            </div>
            @endif
            <span class="photo-count"><i class="fa-solid fa-image me-1"></i>{{ $album->photos_count }}</span>
        </div>
        <div class="album-info">
            <p class="album-title">{{ $album->judul }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="album-date">
                    <i class="fa-regular fa-calendar me-1"></i>
                    {{ $album->tanggal_kegiatan?->translatedFormat('d F Y') ?? $album->created_at->translatedFormat('d F Y') }}
                </span>
                @if(auth()->user()->isAdmin())
                <div class="d-flex gap-1" onclick="event.preventDefault()">
                    <a href="{{ route('albums.edit', $album) }}" class="btn btn-xs btn-outline-secondary" style="padding:.15rem .4rem;font-size:.7rem">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('albums.destroy', $album) }}" id="del-album-{{ $album->id }}">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-xs btn-outline-danger btn-delete" data-form="del-album-{{ $album->id }}"
                            style="padding:.15rem .4rem;font-size:.7rem">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </a>
    @endforeach
</div>

<div class="mt-4">{{ $albums->links() }}</div>
@endif
@endsection
