@extends('layouts.app')

@section('title', 'Upload Renungan')
@section('page-title', 'Upload Renungan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('devotions.index') }}" class="text-decoration-none text-muted">Renungan</a></li>
    <li class="breadcrumb-item active">Upload</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-book-open-reader text-warning me-2"></i>Form Upload Renungan</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info alert-dismissible d-flex gap-2 mb-4" style="font-size:.82rem">
                    <i class="fa-solid fa-circle-info mt-1 flex-shrink-0"></i>
                    <div>Renungan yang Anda upload akan ditinjau oleh admin sebelum dipublikasikan. Pastikan konten sesuai dengan nilai-nilai Kristiani.</div>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
                <form method="POST" action="{{ route('devotions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul Renungan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul') }}" placeholder="Judul yang menarik" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ayat Pendukung</label>
                        <input type="text" name="ayat_pendukung" class="form-control @error('ayat_pendukung') is-invalid @enderror"
                               value="{{ old('ayat_pendukung') }}" placeholder="e.g. Yohanes 3:16">
                        @error('ayat_pendukung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Cover</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg" id="gambarInput">
                        <div class="form-text">Format: JPG, PNG. Maks: 2MB</div>
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="previewWrapper" class="mt-2" style="display:none">
                            <img id="imgPreview" class="rounded-3 border" style="max-height:160px;max-width:100%">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Renungan <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="10" class="form-control @error('isi') is-invalid @enderror"
                                  placeholder="Tuangkan renungan Anda di sini..." required>{{ old('isi') }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-paper-plane me-1"></i>Kirim Renungan
                        </button>
                        <a href="{{ route('devotions.index') }}" class="btn btn-outline-secondary">Batal</a>
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
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('previewWrapper').style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
@endpush
