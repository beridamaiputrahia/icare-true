@extends('layouts.app')

@section('title', 'Tambah Anggota')
@section('page-title', 'Tambah Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}" class="text-decoration-none text-muted">Anggota</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-3">

    {{-- ── Main Form ──────────────────────────────────────────── --}}
    <div class="col-12 col-lg-8">

        {{-- Data Profil --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user-plus text-primary me-2"></i>Data Profil Anggota</h6>
            </div>
            <div class="card-body">

                {{-- Foto --}}
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img id="fotoPreview"
                             src="https://ui-avatars.com/api/?name=Anggota&background=2563eb&color=fff&size=96"
                             class="rounded-circle border" style="width:96px;height:96px;object-fit:cover">
                        <label for="foto" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                               style="width:30px;height:30px;cursor:pointer" title="Upload foto">
                            <i class="fa-solid fa-camera" style="font-size:.7rem"></i>
                            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg"
                                   class="d-none @error('foto') is-invalid @enderror">
                        </label>
                    </div>
                    <div class="text-muted mt-1" style="font-size:.72rem">Klik ikon kamera untuk upload foto</div>
                    @error('foto')<div class="text-danger mt-1" style="font-size:.775rem">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap"
                               class="form-control @error('nama_lengkap') is-invalid @enderror"
                               value="{{ old('nama_lengkap') }}" required autofocus>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan"
                               class="form-control @error('nama_panggilan') is-invalid @enderror"
                               value="{{ old('nama_panggilan') }}">
                        @error('nama_panggilan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nomor_hp"
                               class="form-control @error('nomor_hp') is-invalid @enderror"
                               value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx">
                        @error('nomor_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                               class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}">
                        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Covenant</label>
                        <input type="text" name="covenant_number"
                               class="form-control @error('covenant_number') is-invalid @enderror"
                               value="{{ old('covenant_number') }}" placeholder="Opsional">
                        @error('covenant_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end pb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   value="1" {{ old('is_active','1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_active">Anggota Aktif</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="form-control @error('alamat') is-invalid @enderror"
                                  placeholder="Alamat lengkap anggota">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Akun Login (opsional) --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-shield-halved text-success me-2"></i>Akun Login <span class="text-muted fw-normal" style="font-size:.78rem">(opsional)</span></h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="create_account" name="create_account"
                           value="1" {{ old('create_account') ? 'checked' : '' }} onchange="toggleAccount(this.checked)">
                    <label class="form-check-label fw-medium" for="create_account">
                        Buat akun login untuk anggota ini
                    </label>
                </div>

                <div id="account_fields" style="{{ old('create_account') ? '' : 'display:none' }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="email@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="user"  {{ old('role','user') === 'user'  ? 'selected' : '' }}>User (Anggota)</option>
                                <option value="admin" {{ old('role')        === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 karakter" autocomplete="new-password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" placeholder="Ulangi password" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Anggota
            </button>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>

    </div>

    {{-- ── Tips ────────────────────────────────────────────────── --}}
    <div class="col-12 col-lg-4">
        <div class="card" style="border-color:#dbeafe;background:#eff6ff">
            <div class="card-body">
                <h6 class="fw-semibold mb-3" style="color:#1e40af;font-size:.85rem">
                    <i class="fa-solid fa-circle-info me-2"></i>Panduan Pengisian
                </h6>
                <ul class="mb-0" style="font-size:.8rem;color:#1e3a8a;padding-left:1.25rem;line-height:1.8">
                    <li><strong>Nama Lengkap</strong> wajib diisi</li>
                    <li><strong>Nomor Covenant</strong> harus unik jika diisi</li>
                    <li><strong>Akun Login</strong> opsional — buat jika anggota ingin mengakses aplikasi</li>
                    <li>Role <strong>Admin</strong> dapat mengelola semua data</li>
                    <li>Role <strong>User</strong> hanya dapat melihat dan membuat konten</li>
                    <li>Akun juga dapat dibuat nanti melalui halaman Edit</li>
                </ul>
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('foto').addEventListener('change', function() {
    if (this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('fotoPreview').src = e.target.result;
        reader.readAsDataURL(this.files[0]);
    }
});

function toggleAccount(show) {
    document.getElementById('account_fields').style.display = show ? '' : 'none';
}
</script>
@endpush
