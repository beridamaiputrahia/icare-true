@extends('layouts.app')

@section('title', 'Edit Doa')
@section('page-title', 'Edit Doa')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('prayers.index') }}" class="text-decoration-none text-muted">Doa</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-pen text-warning me-2"></i>Edit Doa</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('prayers.update', $prayer) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Judul Doa <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $prayer->judul) }}" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pengirim</label>
                        <input type="text" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror"
                               value="{{ old('pengirim', $prayer->pengirim) }}"
                               {{ $prayer->is_anonymous ? 'disabled' : '' }} id="pengirimInput">
                        @error('pengirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous"
                                   value="1" {{ old('is_anonymous', $prayer->is_anonymous) ? 'checked':'' }}
                                   onchange="toggleAnon(this)">
                            <label class="form-check-label" for="is_anonymous" style="font-size:.875rem">Kirim sebagai <strong>Anonim</strong></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isi Doa <span class="text-danger">*</span></label>
                        <textarea name="isi_doa" rows="7" class="form-control @error('isi_doa') is-invalid @enderror" required>{{ old('isi_doa', $prayer->isi_doa) }}</textarea>
                        @error('isi_doa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
                        <a href="{{ route('prayers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleAnon(cb) {
    const pengirim = document.getElementById('pengirimInput');
    pengirim.disabled = cb.checked;
    if (cb.checked) pengirim.value = '';
}
</script>
@endpush
