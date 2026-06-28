@extends('layouts.app')

@section('title', 'Tambah Pengumuman')
@section('page-title', 'Tambah Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted">Pengumuman</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-bullhorn text-warning me-2"></i>Form Pengumuman Baru</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul') }}" placeholder="Judul pengumuman" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penulis <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
                               value="{{ old('penulis', auth()->user()->name) }}" required>
                        @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/gif" id="gambarInput">
                        <div class="form-text">Format: JPG, PNG, GIF. Maks: 2MB</div>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="previewWrapper" class="mt-2" style="display:none">
                            <img id="imgPreview" class="rounded-3 border" style="max-height:160px;max-width:100%">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="8" class="form-control @error('isi') is-invalid @enderror"
                                  placeholder="Tulis isi pengumuman di sini..." required>{{ old('isi') }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                                   {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publikasikan sekarang</label>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('gambarInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('previewWrapper').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
