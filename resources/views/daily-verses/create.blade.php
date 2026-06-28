@extends('layouts.app')

@section('title', 'Tambah Ayat Harian')
@section('page-title', 'Tambah Ayat Harian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('daily-verses.index') }}" class="text-decoration-none text-muted">Ayat Harian</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-bible text-primary me-2"></i>Form Ayat Harian</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('daily-verses.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Referensi Ayat <span class="text-danger">*</span></label>
                        <input type="text" name="referensi" class="form-control @error('referensi') is-invalid @enderror"
                               value="{{ old('referensi') }}" placeholder="e.g. Yohanes 3:16" required>
                        @error('referensi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ayat Firman Tuhan <span class="text-danger">*</span></label>
                        <textarea name="ayat" rows="4" class="form-control @error('ayat') is-invalid @enderror"
                                  placeholder="Tuliskan teks ayat di sini..." required>{{ old('ayat') }}</textarea>
                        @error('ayat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', today()->format('Y-m-d')) }}">
                        <div class="form-text">Tentukan tanggal tampil ayat ini di dashboard.</div>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Renungan Singkat</label>
                        <textarea name="renungan_singkat" rows="4" class="form-control @error('renungan_singkat') is-invalid @enderror"
                                  placeholder="Renungan singkat untuk ayat ini...">{{ old('renungan_singkat') }}</textarea>
                        @error('renungan_singkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktifkan sebagai ayat harian</label>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Ayat</button>
                        <a href="{{ route('daily-verses.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
