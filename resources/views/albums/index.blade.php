@extends('layouts.app')

@section('title', 'Galeri Kegiatan')
@section('page-title', 'Galeri Kegiatan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Galeri</li>
@endsection

@section('page-actions')
@if(auth()->user()->isAdmin())
<a href="{{ route('albums.create') }}" class="btn btn-sm btn-primary">
    <i class="fa-solid fa-plus me-1"></i>Posting Album
</a>
@endif
@endsection

@push('styles')
<style>
.album-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:1.1rem !important; }
.album-card {
    display:flex !important; flex-direction:column !important;
    border-radius:14px !important; overflow:hidden !important;
    background:var(--surface,#fff) !important;
    border:1px solid var(--border,#e2e8f0) !important;
    box-shadow:0 1px 3px rgba(0,0,0,.08) !important;
    transition:transform .2s,box-shadow .2s; text-decoration:none !important; color:inherit;
    margin:0 !important; padding:0 !important; cursor:pointer;
}
.album-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.12); color:inherit; }
.album-cover {
    aspect-ratio:16/10; background:var(--surface-2,#e2e8f0);
    position:relative; overflow:hidden; flex-shrink:0; margin:0 !important;
}
.album-cover img { width:100%; height:100%; object-fit:cover; display:block; margin:0; transition:transform .3s; }
.album-card:hover .album-cover img { transform:scale(1.05); }
.cover-placeholder { display:flex; align-items:center; justify-content:center; height:100%; background:linear-gradient(135deg,var(--surface-2,#e2e8f0),var(--border,#cbd5e1)); }
.photo-count {
    position:absolute; bottom:.5rem; right:.5rem;
    background:rgba(0,0,0,.65); color:#fff; border-radius:20px;
    padding:.2rem .6rem; font-size:.72rem; font-weight:600;
    display:flex; align-items:center; gap:.3rem; line-height:1; margin:0;
}
.album-info {
    padding:.9rem 1rem 1rem !important; margin:0 !important;
    display:flex; flex-direction:column; gap:.5rem; flex:1;
    background:transparent !important; border:none !important; border-radius:0 !important;
}
p.album-title {
    font-size:.92rem; font-weight:700; color:var(--text,#1e293b) !important;
    margin:0 !important; padding:0 !important; line-height:1.35;
    background:transparent !important; border:none !important; border-radius:0 !important;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.album-meta { display:flex; align-items:center; justify-content:space-between; gap:.5rem; margin:auto 0 0 !important; padding:0; }
.album-date {
    font-size:.74rem; color:var(--text-muted,#64748b) !important;
    display:flex; align-items:center; gap:.35rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    margin:0; padding:0; background:transparent !important; border:none !important;
}
.album-actions { display:flex; gap:.35rem; flex-shrink:0; margin:0; }
.album-actions .btn {
    width:28px; height:28px; padding:0 !important; display:flex; align-items:center; justify-content:center;
    font-size:.72rem; border-radius:8px; margin:0 !important;
}
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
        <i class="fa-solid fa-plus me-1"></i>Posting Album Pertama
    </a>
    @endif
</div>
@else
<div class="album-grid">
    @foreach($albums as $album)
    <div class="album-card" onclick="if(!event.target.closest('.album-actions')) window.location='{{ route('albums.show', $album) }}'">
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
            <div class="album-meta">
                <span class="album-date">
                    <i class="fa-regular fa-calendar"></i>
                    {{ $album->tanggal_kegiatan?->translatedFormat('d M Y') ?? $album->created_at->translatedFormat('d M Y') }}
                </span>
                @if(auth()->user()->isAdmin())
                <div class="album-actions">
                    <a href="{{ route('albums.edit', $album) }}" class="btn btn-outline-secondary" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form method="POST" action="{{ route('albums.destroy', $album) }}" id="del-album-{{ $album->id }}">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-outline-danger btn-delete" data-form="del-album-{{ $album->id }}" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">{{ $albums->links() }}</div>
@endif
@endsection
