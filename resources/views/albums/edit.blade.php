@extends('layouts.app')

@section('title', 'Edit Album')
@section('page-title', 'Edit Album')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('albums.index') }}" class="text-decoration-none text-muted">Galeri</a></li>
    <li class="breadcrumb-item"><a href="{{ route('albums.show', $album) }}" class="text-decoration-none text-muted">{{ Str::limit($album->judul,30) }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('albums.update', $album) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Judul Album <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $album->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $album->deskripsi) }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cover Album</label>
                            @if($album->cover_url)
                            <div class="mb-2">
                                <img src="{{ $album->cover_url }}" class="rounded-3" style="height:80px;object-fit:cover">
                            </div>
                            @endif
                            <input type="file" name="cover" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kegiatan</label>
                            <input type="date" name="tanggal_kegiatan" class="form-control"
                                   value="{{ old('tanggal_kegiatan', $album->tanggal_kegiatan?->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="is_published"
                                   {{ old('is_published', $album->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">Publikasikan Album</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('albums.show', $album) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
