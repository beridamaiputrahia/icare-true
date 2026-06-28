@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted">Pengumuman</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-pen text-warning me-2"></i>Edit: {{ Str::limit($announcement->judul, 40) }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('announcements.update', $announcement) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $announcement->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penulis <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
                               value="{{ old('penulis', $announcement->penulis) }}" required>
                        @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        @if($announcement->gambar)
                        <div class="mb-2">
                            <img src="{{ Storage::url($announcement->gambar) }}" class="rounded-3 border" style="max-height:120px">
                            <p class="text-muted mt-1 mb-0" style="font-size:.75rem">Gambar saat ini. Upload baru untuk mengganti.</p>
                        </div>
                        @endif
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg,image/gif">
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Pengumuman <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="8" class="form-control @error('isi') is-invalid @enderror" required>{{ old('isi', $announcement->isi) }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published" value="1"
                                   {{ old('is_published', $announcement->is_published ? '1' : '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Dipublikasikan</label>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-floppy-disk me-1"></i>Perbarui</button>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
