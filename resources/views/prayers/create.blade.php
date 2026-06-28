@extends('layouts.app')

@section('title', 'Kirim Doa')
@section('page-title', 'Kirim Doa')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('prayers.index') }}" class="text-decoration-none text-muted">Doa</a></li>
    <li class="breadcrumb-item active">Kirim</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-hands-praying text-primary me-2"></i>Form Pengiriman Doa</h6>
            </div>
            <div class="card-body">
                <div class="verse-card mb-4">
                    <p class="verse-text mb-1" style="font-size:.85rem">"Janganlah hendaknya kamu kuatir tentang apapun juga, tetapi nyatakanlah dalam segala hal keinginanmu kepada Allah dalam doa dan permohonan dengan ucapan syukur."</p>
                    <p class="verse-ref mb-0" style="font-size:.75rem">— Filipi 4:6</p>
                </div>

                <form method="POST" action="{{ route('prayers.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Judul Doa <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul') }}" placeholder="Ringkasan pokok doa" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pengirim</label>
                        <input type="text" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror"
                               value="{{ old('pengirim', auth()->user()->name) }}"
                               placeholder="Nama Anda (kosongkan jika anonim)" id="pengirimInput">
                        @error('pengirim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous"
                                   value="1" {{ old('is_anonymous') ? 'checked':'' }} onchange="toggleAnon(this)">
                            <label class="form-check-label" for="is_anonymous" style="font-size:.875rem">
                                Kirim sebagai <strong>Anonim</strong>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Isi Doa <span class="text-danger">*</span></label>
                        <textarea name="isi_doa" rows="7" class="form-control @error('isi_doa') is-invalid @enderror"
                                  placeholder="Tuangkan pokok doa Anda di sini..." required>{{ old('isi_doa') }}</textarea>
                        @error('isi_doa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-info d-flex gap-2" style="font-size:.8rem">
                        <i class="fa-solid fa-circle-info flex-shrink-0 mt-1"></i>
                        <div>Doa Anda akan ditinjau oleh admin sebelum ditampilkan kepada komunitas untuk didoakan bersama.</div>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-hands-praying me-1"></i>Kirim Doa
                        </button>
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
    else pengirim.value = '{{ auth()->user()->name }}';
}
</script>
@endpush
