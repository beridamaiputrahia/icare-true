@extends('layouts.app')

@section('title', $album->judul)
@section('page-title', 'Galeri')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('albums.index') }}" class="text-decoration-none text-muted">Galeri</a></li>
    <li class="breadcrumb-item active">{{ Str::limit($album->judul, 40) }}</li>
@endsection

@section('page-actions')
@if(auth()->user()->isAdmin())
<div class="d-flex gap-2">
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fa-solid fa-upload me-1"></i>Upload Foto
    </button>
    <a href="{{ route('albums.edit', $album) }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa-solid fa-pen me-1"></i>Edit Album
    </a>
</div>
@endif
@endsection

@push('styles')
<style>
/* Masonry grid */
.masonry-grid { columns: 3; column-gap: .85rem; }
@media (max-width:991px) { .masonry-grid { columns: 2; } }
@media (max-width:575px) { .masonry-grid { columns: 1; } }

.photo-item {
    break-inside: avoid; margin-bottom: .85rem;
    border-radius: 12px; overflow: hidden; position: relative;
    cursor: pointer; display: block;
    background: #e2e8f0;
    box-shadow: 0 1px 4px rgba(0,0,0,.1); transition: transform .2s, box-shadow .2s;
}
.photo-item:hover { transform: scale(1.01); box-shadow: 0 6px 20px rgba(0,0,0,.15); }
.photo-item img { width: 100%; display: block; }
.photo-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,.0);
    transition: background .2s; display: flex; flex-direction: column;
    justify-content: flex-end; padding: .75rem;
}
.photo-item:hover .photo-overlay { background: rgba(0,0,0,.45); }
.photo-caption { color: #fff; font-size: .78rem; opacity: 0; transition: opacity .2s; }
.photo-item:hover .photo-caption { opacity: 1; }
.photo-actions { position: absolute; top: 8px; right: 8px; display: flex; gap: 4px; opacity: 0; transition: opacity .2s; }
.photo-item:hover .photo-actions { opacity: 1; }
.photo-action-btn { width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,.9); border: none; display: flex; align-items: center; justify-content: center; font-size: .75rem; cursor: pointer; color: #1e293b; transition: background .15s; }
.photo-action-btn:hover { background: #fff; }

/* Lightbox */
#lightbox {
    position: fixed; inset: 0; z-index: 10000; background: rgba(0,0,0,.94);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    display: none;
}
#lightbox.open { display: flex; }
.lb-img-wrap { max-width: 90vw; max-height: 80vh; display: flex; align-items: center; justify-content: center; }
#lb-img { max-width: 100%; max-height: 80vh; border-radius: 8px; box-shadow: 0 8px 40px rgba(0,0,0,.6); }
.lb-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,.15); border: none; color: #fff; width: 48px; height: 48px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .2s; }
.lb-nav:hover { background: rgba(255,255,255,.3); }
#lb-prev { left: 1rem; }
#lb-next { right: 1rem; }
#lb-close { position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,.15); border: none; color: #fff; width: 40px; height: 40px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
#lb-caption { color: rgba(255,255,255,.7); font-size: .82rem; margin-top: .75rem; text-align: center; max-width: 600px; }
#lb-counter { position: absolute; top: 1rem; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,.6); font-size: .78rem; }
</style>
@endpush

@section('content')

{{-- Album Header ──────────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center g-3">
            @if($album->cover_url)
            <div class="col-auto">
                <img src="{{ $album->cover_url }}" alt="{{ $album->judul }}"
                     class="rounded-3" style="width:80px;height:80px;object-fit:cover">
            </div>
            @endif
            <div class="col">
                <h4 class="fw-bold mb-1">{{ $album->judul }}</h4>
                <div class="d-flex gap-3 text-muted" style="font-size:.8rem">
                    <span><i class="fa-regular fa-calendar me-1"></i>
                        {{ $album->tanggal_kegiatan?->translatedFormat('d F Y') ?? $album->created_at->translatedFormat('d F Y') }}
                    </span>
                    <span><i class="fa-solid fa-images me-1"></i>{{ $photos->count() }} Foto</span>
                    <span><i class="fa-solid fa-user me-1"></i>{{ $album->user->name }}</span>
                </div>
                @if($album->deskripsi)
                <p class="mt-2 mb-0 text-muted" style="font-size:.85rem">{{ $album->deskripsi }}</p>
                @endif
            </div>
            <div class="col-auto">
                <a href="{{ route('albums.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Photos Masonry ────────────────────────────────────────────── --}}
@if($photos->isEmpty())
<div class="text-center py-5 text-muted">
    <i class="fa-solid fa-camera fa-3x mb-3 d-block opacity-25"></i>
    <p class="mb-0">Belum ada foto di album ini.</p>
    @if(auth()->user()->isAdmin())
    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fa-solid fa-upload me-1"></i>Upload Foto Pertama
    </button>
    @endif
</div>
@else
<div class="masonry-grid" id="photoGrid">
    @foreach($photos as $i => $photo)
    <div class="photo-item" data-index="{{ $i }}" onclick="openLightbox({{ $i }})">
        <img src="{{ $photo->url }}" alt="{{ $photo->caption ?? 'Foto ' . ($i+1) }}" loading="lazy">
        <div class="photo-overlay">
            @if($photo->caption)
            <p class="photo-caption mb-0">{{ $photo->caption }}</p>
            @endif
        </div>
        <div class="photo-actions" onclick="event.stopPropagation()">
            <a href="{{ route('photos.download', [$album, $photo]) }}" class="photo-action-btn" title="Download" download>
                <i class="fa-solid fa-download"></i>
            </a>
            @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('photos.destroy', [$album, $photo]) }}" id="del-photo-{{ $photo->id }}">
                @csrf @method('DELETE')
            </form>
            <button class="photo-action-btn btn-delete" data-form="del-photo-{{ $photo->id }}" title="Hapus" style="color:#ef4444">
                <i class="fa-solid fa-trash"></i>
            </button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Lightbox ─────────────────────────────────────────────────── --}}
<div id="lightbox">
    <span id="lb-counter"></span>
    <button id="lb-close" onclick="closeLightbox()"><i class="fa-solid fa-xmark"></i></button>
    <button class="lb-nav" id="lb-prev" onclick="lbStep(-1)"><i class="fa-solid fa-chevron-left"></i></button>
    <div class="lb-img-wrap">
        <img id="lb-img" src="" alt="">
    </div>
    <button class="lb-nav" id="lb-next" onclick="lbStep(1)"><i class="fa-solid fa-chevron-right"></i></button>
    <p id="lb-caption"></p>
</div>

{{-- Upload Modal (Admin) ─────────────────────────────────────── --}}
@if(auth()->user()->isAdmin())
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('photos.store', $album) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0">
                    <h6 class="modal-title fw-semibold"><i class="fa-solid fa-upload text-primary me-2"></i>Upload Foto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="p-4 border-2 border-dashed rounded-3 text-center mb-3" id="dropzone"
                         style="border:2px dashed #cbd5e1;cursor:pointer" onclick="document.getElementById('photoFiles').click()">
                        <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2 d-block"></i>
                        <p class="mb-1 fw-semibold text-muted">Klik atau drag & drop foto</p>
                        <p class="text-muted small mb-0">JPG, PNG, WebP • Maks 8MB per foto • Maks 30 foto</p>
                        <input type="file" name="photos[]" id="photoFiles" multiple accept="image/*" class="d-none">
                    </div>
                    <div id="previewGrid" class="row g-2"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-upload me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// ── Photos data for lightbox ──────────────────────────────────
const PHOTOS = @json($photos->map(fn($p) => ['url' => $p->url, 'caption' => $p->caption]));
let currentIdx = 0;

function openLightbox(idx) {
    currentIdx = idx;
    renderLightbox();
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
function lbStep(dir) {
    currentIdx = (currentIdx + dir + PHOTOS.length) % PHOTOS.length;
    renderLightbox();
}
function renderLightbox() {
    const p = PHOTOS[currentIdx];
    document.getElementById('lb-img').src = p.url;
    document.getElementById('lb-caption').textContent = p.caption ?? '';
    document.getElementById('lb-counter').textContent = (currentIdx + 1) + ' / ' + PHOTOS.length;
}
document.addEventListener('keydown', e => {
    if (!document.getElementById('lightbox').classList.contains('open')) return;
    if (e.key === 'ArrowRight') lbStep(1);
    if (e.key === 'ArrowLeft')  lbStep(-1);
    if (e.key === 'Escape')     closeLightbox();
});
document.getElementById('lightbox').addEventListener('click', e => {
    if (e.target === document.getElementById('lightbox')) closeLightbox();
});

// ── Upload preview ────────────────────────────────────────────
document.getElementById('photoFiles')?.addEventListener('change', function() {
    const grid = document.getElementById('previewGrid');
    grid.innerHTML = '';
    Array.from(this.files).slice(0, 30).forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            grid.insertAdjacentHTML('beforeend', `
                <div class="col-4 col-md-3">
                    <img src="${e.target.result}" class="img-fluid rounded-2" style="height:80px;object-fit:cover;width:100%">
                </div>
            `);
        };
        reader.readAsDataURL(file);
    });
});

// Drag-drop
const dz = document.getElementById('dropzone');
if (dz) {
    dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor = 'var(--app-primary)'; });
    dz.addEventListener('dragleave', () => { dz.style.borderColor = '#cbd5e1'; });
    dz.addEventListener('drop', e => {
        e.preventDefault();
        dz.style.borderColor = '#cbd5e1';
        const inp = document.getElementById('photoFiles');
        const dt  = new DataTransfer();
        Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
        inp.files = dt.files;
        inp.dispatchEvent(new Event('change'));
    });
}
</script>
@endpush
