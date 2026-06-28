@extends('layouts.app')

@section('title', 'Buat Album')
@section('page-title', 'Buat Album Baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('albums.index') }}" class="text-decoration-none text-muted">Galeri</a></li>
    <li class="breadcrumb-item active">Buat Album</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('albums.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Judul Album <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul') }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                  rows="3">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cover Album</label>
                            <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror" accept="image/*">
                            @error('cover')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kegiatan</label>
                            <input type="date" name="tanggal_kegiatan" class="form-control" value="{{ old('tanggal_kegiatan', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Foto (Opsional)</label>
                        <div class="p-4 border rounded-3 text-center" style="border:2px dashed #cbd5e1;cursor:pointer"
                             onclick="document.getElementById('photos').click()">
                            <i class="fa-solid fa-images fa-2x text-muted mb-2 d-block"></i>
                            <p class="mb-1 fw-semibold text-muted">Klik untuk pilih foto</p>
                            <p class="text-muted small mb-0">Maks 30 foto, 8MB per foto</p>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="d-none">
                        </div>
                        <div id="previewGrid" class="row g-2 mt-2"></div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published"
                                   {{ old('is_published', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publikasikan Album</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-1"></i>Buat Album
                        </button>
                        <a href="{{ route('albums.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photos').addEventListener('change', function() {
    const grid = document.getElementById('previewGrid');
    grid.innerHTML = '';
    Array.from(this.files).slice(0, 30).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            grid.insertAdjacentHTML('beforeend', `
                <div class="col-4 col-md-2">
                    <img src="${e.target.result}" class="img-fluid rounded-2" style="height:70px;object-fit:cover;width:100%">
                </div>
            `);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
