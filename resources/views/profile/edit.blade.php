@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb')
    <li class="breadcrumb-item active">Profil</li>
@endsection

@push('styles')
<style>
.avatar-upload-wrap {
    position: relative; width: 90px; height: 90px;
}
.avatar-upload-wrap img, .avatar-upload-wrap .avatar-placeholder {
    width: 90px; height: 90px; border-radius: 50%; object-fit: cover;
    border: 3px solid #e2e8f0;
}
.avatar-placeholder {
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg,#2563eb,#7c3aed);
    font-size: 2rem; font-weight: 700; color: #fff;
}
.avatar-btn {
    position: absolute; bottom: 0; right: 0;
    width: 28px; height: 28px; border-radius: 50%;
    background: #2563eb; color: #fff; border: 2px solid #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: .75rem;
}
</style>
@endpush

@section('content')
<div class="row g-3 justify-content-center">
    <div class="col-12 col-lg-8">

        @if(session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible d-flex gap-2 mb-3" style="font-size:.82rem">
            <i class="fa-solid fa-circle-check flex-shrink-0 mt-1"></i>
            <div>Profil berhasil diperbarui.</div>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible d-flex gap-2 mb-3" style="font-size:.82rem">
            <i class="fa-solid fa-circle-check flex-shrink-0 mt-1"></i>
            <div>Kata sandi berhasil diubah.</div>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- ── Informasi Profil ─────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-user text-primary me-2"></i>Informasi Profil
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    {{-- Avatar --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="avatar-upload-wrap">
                            @if($member?->foto)
                            <img id="avatar-preview" src="{{ Storage::url($member->foto) }}" alt="Foto">
                            @else
                            <div class="avatar-placeholder" id="avatar-preview-placeholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="avatar-preview" src="" alt="Foto" style="display:none">
                            @endif
                            <label class="avatar-btn" for="foto" title="Ganti foto">
                                <i class="fa-solid fa-camera"></i>
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*" class="d-none"
                                   onchange="previewAvatar(this)">
                        </div>
                        <div>
                            <p class="fw-semibold mb-0" style="font-size:.9rem">Foto Profil</p>
                            <p class="text-muted mb-0" style="font-size:.75rem">JPG, PNG, max 2MB</p>
                            @error('foto')<p class="text-danger mb-0" style="font-size:.75rem">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Nama Lengkap --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap"
                                   class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   value="{{ old('nama_lengkap', $member?->nama_lengkap ?? $user->name) }}" required>
                            @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Nama Panggilan --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Nama Panggilan</label>
                            <input type="text" name="nama_panggilan"
                                   class="form-control @error('nama_panggilan') is-invalid @enderror"
                                   value="{{ old('nama_panggilan', $member?->nama_panggilan) }}"
                                   placeholder="Opsional">
                            @error('nama_panggilan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                   class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                   value="{{ old('tanggal_lahir', $member?->tanggal_lahir?->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Nomor HP --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Nomor HP</label>
                            <input type="tel" name="nomor_hp"
                                   class="form-control @error('nomor_hp') is-invalid @enderror"
                                   value="{{ old('nomor_hp', $member?->nomor_hp) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('nomor_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12">
                            <label class="form-label fw-medium" style="font-size:.83rem">Alamat</label>
                            <input type="text" name="alamat"
                                   class="form-control @error('alamat') is-invalid @enderror"
                                   value="{{ old('alamat', $member?->alamat) }}"
                                   placeholder="Alamat tempat tinggal">
                            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Covenant Toggle --}}
                        <div class="col-12">
                            <label class="form-label fw-medium d-flex align-items-center justify-content-between mb-1" style="font-size:.83rem">
                                <span>Nomor Covenant</span>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="covenant_aktif" name="covenant_aktif"
                                           value="1"
                                           {{ old('covenant_aktif', $member?->covenant_number ? '1' : null) ? 'checked' : '' }}
                                           onchange="toggleCovenantProfile(this.checked)">
                                    <label class="form-check-label" for="covenant_aktif" style="font-size:.78rem;color:#64748b">
                                        Aktifkan
                                    </label>
                                </div>
                            </label>
                            <div id="covenant_field" style="{{ old('covenant_aktif', $member?->covenant_number) ? '' : 'display:none' }}">
                                <input type="text" name="covenant_number"
                                       class="form-control @error('covenant_number') is-invalid @enderror"
                                       value="{{ old('covenant_number', $member?->covenant_number) }}"
                                       placeholder="Nomor covenant Anda">
                                @error('covenant_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-12">
                            <label class="form-label fw-medium" style="font-size:.83rem">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Role (readonly) --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Role</label>
                            <input type="text" class="form-control bg-light" value="{{ ucfirst($user->role) }}" readonly>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Ubah Kata Sandi ─────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-lock text-warning me-2"></i>Ubah Kata Sandi
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium" style="font-size:.83rem">Kata Sandi Lama <span class="text-danger">*</span></label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   autocomplete="current-password" placeholder="Kata sandi saat ini">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Kata Sandi Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   autocomplete="new-password" placeholder="Min. 8 karakter">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-medium" style="font-size:.83rem">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" autocomplete="new-password"
                                   placeholder="Ulangi kata sandi baru">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning">
                            <i class="fa-solid fa-key me-1"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Hapus Akun ───────────────────────────────────────────── --}}
        <div class="card border-danger">
            <div class="card-header border-danger" style="background:#fff5f5">
                <h6 class="mb-0 fw-semibold text-danger">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Hapus Akun
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3" style="font-size:.875rem">
                    Setelah akun dihapus, semua data Anda akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
                <button type="button" class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="fa-solid fa-trash me-1"></i>Hapus Akun Saya
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <div class="modal-header border-0">
                    <h6 class="modal-title fw-semibold text-danger">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>Hapus Akun?
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3" style="font-size:.875rem">
                        Konfirmasi dengan memasukkan kata sandi Anda saat ini.
                    </p>
                    <input type="password" name="password"
                           class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                           placeholder="Kata sandi Anda">
                    @error('password', 'userDeletion')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash me-1"></i>Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCovenantProfile(active) {
    const field = document.getElementById('covenant_field');
    const input = field.querySelector('input[name="covenant_number"]');
    field.style.display = active ? '' : 'none';
    if (!active) input.value = '';
}

function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('avatar-preview');
        const placeholder = document.getElementById('avatar-preview-placeholder');
        img.src = e.target.result;
        img.style.display = '';
        if (placeholder) placeholder.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
