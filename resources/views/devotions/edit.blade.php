@extends('layouts.app')

@section('title', 'Edit Renungan')
@section('page-title', 'Edit Renungan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('devotions.index') }}" class="text-decoration-none text-muted">Renungan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-pen text-warning me-2"></i>Edit Renungan</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning d-flex gap-2 mb-4" style="font-size:.82rem">
                    <i class="fa-solid fa-triangle-exclamation mt-1 flex-shrink-0"></i>
                    <div>Mengedit renungan akan mengubah statusnya kembali ke <strong>Menunggu Persetujuan</strong>.</div>
                </div>
                <form method="POST" action="{{ route('devotions.update', $devotion) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Judul Renungan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $devotion->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ayat Pendukung</label>
                        <input type="text" name="ayat_pendukung" class="form-control @error('ayat_pendukung') is-invalid @enderror"
                               value="{{ old('ayat_pendukung', $devotion->ayat_pendukung) }}">
                        @error('ayat_pendukung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Cover</label>
                        @if($devotion->gambar)
                        <div class="mb-2">
                            <img src="{{ Storage::url($devotion->gambar) }}" class="rounded-3 border" style="max-height:120px">
                            <p class="text-muted mt-1 mb-0" style="font-size:.75rem">Gambar saat ini. Upload baru untuk mengganti.</p>
                        </div>
                        @endif
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/jpg">
                        @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Renungan <span class="text-danger">*</span></label>
                        <textarea name="isi" rows="10" class="form-control @error('isi') is-invalid @enderror" required>{{ old('isi', $devotion->isi) }}</textarea>
                        @error('isi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Perubahan</button>
                        <a href="{{ route('devotions.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
