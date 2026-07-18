@extends('layouts.app')

@section('title', 'Edit Anggota')
@section('page-title', 'Edit Data Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}" class="text-decoration-none text-muted">Anggota</a></li>
    <li class="breadcrumb-item"><a href="{{ route('members.show', $member) }}" class="text-decoration-none text-muted">{{ $member->nama_lengkap }}</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

<div class="row g-3">

    {{-- ── Left Column ────────────────────────────────────────── --}}
    <div class="col-12 col-lg-8">

        {{-- Profil --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user-pen text-warning me-2"></i>Data Profil</h6>
            </div>
            <div class="card-body">

                {{-- Foto --}}
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img id="fotoPreview"
                             src="{{ $member->foto ? Storage::url($member->foto) : 'https://ui-avatars.com/api/?name='.urlencode($member->nama_lengkap).'&background=2563eb&color=fff&size=96' }}"
                             class="rounded-circle border" style="width:96px;height:96px;object-fit:cover">
                        <label for="foto" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle p-1"
                               style="width:30px;height:30px;cursor:pointer" title="Ganti foto">
                            <i class="fa-solid fa-camera" style="font-size:.7rem"></i>
                            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg" class="d-none">
                        </label>
                    </div>
                    <div class="text-muted mt-1" style="font-size:.72rem">Klik ikon kamera untuk ganti foto</div>
                    @error('foto')<div class="text-danger mt-1" style="font-size:.775rem">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap"
                               class="form-control @error('nama_lengkap') is-invalid @enderror"
                               value="{{ old('nama_lengkap', $member->nama_lengkap) }}" required>
                        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Panggilan</label>
                        <input type="text" name="nama_panggilan"
                               class="form-control @error('nama_panggilan') is-invalid @enderror"
                               value="{{ old('nama_panggilan', $member->nama_panggilan) }}">
                        @error('nama_panggilan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="nomor_hp"
                               class="form-control @error('nomor_hp') is-invalid @enderror"
                               value="{{ old('nomor_hp', $member->nomor_hp) }}" placeholder="08xxxxxxxxxx">
                        @error('nomor_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                               class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               value="{{ old('tanggal_lahir', $member->tanggal_lahir?->format('Y-m-d')) }}">
                        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Covenant</label>
                        <input type="text" name="covenant_number"
                               class="form-control @error('covenant_number') is-invalid @enderror"
                               value="{{ old('covenant_number', $member->covenant_number) }}" placeholder="Opsional">
                        @error('covenant_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end pb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ old('is_active', $member->is_active ? '1' : '') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_active">Anggota Aktif</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="form-control @error('alamat') is-invalid @enderror"
                                  placeholder="Alamat lengkap">{{ old('alamat', $member->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Manajemen Akun --}}
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-shield-halved text-primary me-2"></i>Manajemen Akun
                </h6>
                @if($user)
                <span class="badge bg-{{ $user->roleColor() }} text-uppercase" style="font-size:.65rem;letter-spacing:.05em">
                    {{ $user->roleLabel() }}
                </span>
                @else
                <span class="badge bg-secondary" style="font-size:.65rem">Belum punya akun</span>
                @endif
            </div>
            <div class="card-body">

                @if($user)
                {{-- Akun sudah ada → ubah email, role, reset password --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Email Akun</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope text-muted"></i></span>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role Akun</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="anggota" {{ old('role', $user->role) === 'anggota' ? 'selected' : '' }}>Anggota</option>
                            <option value="ctl"     {{ old('role', $user->role) === 'ctl'     ? 'selected' : '' }}>CTL (Co-Team Leader)</option>
                            <option value="icl"     {{ old('role', $user->role) === 'icl'     ? 'selected' : '' }}>ICL (I Care Leader)</option>
                            <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px dashed #cbd5e1">
                            <p class="fw-semibold mb-2" style="font-size:.82rem;color:#64748b">
                                <i class="fa-solid fa-key me-1"></i>Reset Password (kosongkan jika tidak ingin mengubah)
                            </p>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="password" name="new_password"
                                           class="form-control @error('new_password') is-invalid @enderror"
                                           placeholder="Password baru (min. 8 karakter)" autocomplete="new-password">
                                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <input type="password" name="new_password_confirmation"
                                           class="form-control" placeholder="Konfirmasi password baru" autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @else
                {{-- Tidak ada akun → toggle buat akun baru --}}
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-3" style="font-size:.83rem">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Anggota ini belum memiliki akun login. Buat akun agar dapat mengakses aplikasi.
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="create_account" name="create_account"
                           value="1" {{ old('create_account') ? 'checked' : '' }} onchange="toggleNewAccount(this.checked)">
                    <label class="form-check-label fw-medium" for="create_account">Buat akun baru untuk anggota ini</label>
                </div>
                <div id="new_account_fields" style="{{ old('create_account') ? '' : 'display:none' }}">
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
                            <select name="role" class="form-select">
                                <option value="anggota" {{ old('role','anggota') === 'anggota' ? 'selected' : '' }}>Anggota</option>
                                <option value="ctl"     {{ old('role')           === 'ctl'     ? 'selected' : '' }}>CTL (Co-Team Leader)</option>
                                <option value="icl"     {{ old('role')           === 'icl'     ? 'selected' : '' }}>ICL (I Care Leader)</option>
                                <option value="admin"   {{ old('role')           === 'admin'   ? 'selected' : '' }}>Admin</option>
                            </select>
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
                @endif

            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex gap-2 mb-3">
            <button type="submit" class="btn btn-warning">
                <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Perubahan
            </button>
            <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">Batal</a>
        </div>

    </div>

    {{-- ── Right Column (summary) ──────────────────────────────── --}}
    <div class="col-12 col-lg-4">

        {{-- Info ringkas --}}
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold" style="font-size:.82rem">
                    <i class="fa-solid fa-circle-info text-primary me-2"></i>Ringkasan
                </h6>
            </div>
            <div class="card-body" style="font-size:.83rem">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted fw-medium" style="width:40%">ID Anggota</td>
                        <td><span class="badge bg-light text-dark border">#{{ $member->id }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium">Terdaftar</td>
                        <td>{{ $member->created_at->translatedFormat('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium">Akun</td>
                        <td>
                            @if($user)
                            <span class="text-success fw-medium"><i class="fa-solid fa-check me-1"></i>Terhubung</span>
                            @else
                            <span class="text-muted"><i class="fa-solid fa-xmark me-1"></i>Belum ada</span>
                            @endif
                        </td>
                    </tr>
                    @if($user)
                    <tr>
                        <td class="text-muted fw-medium">Login terakhir</td>
                        <td>{{ $user->last_seen ? $user->last_seen->diffForHumans() : '-' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Quick role change (if account exists) --}}
        @if($user)
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold" style="font-size:.82rem">
                    <i class="fa-solid fa-user-shield text-danger me-2"></i>Ubah Role Cepat
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2" style="font-size:.78rem">
                    Role saat ini: <span class="badge bg-{{ $user->roleColor() }}">{{ $user->roleLabel() }}</span>
                </p>
                <form method="POST" action="{{ route('members.role', $member) }}">
                    @csrf @method('PATCH')
                    <div class="d-flex gap-2">
                        <select name="role" class="form-select form-select-sm">
                            <option value="anggota" {{ $user->role === 'anggota' ? 'selected' : '' }}>Anggota</option>
                            <option value="ctl"     {{ $user->role === 'ctl'     ? 'selected' : '' }}>CTL (Co-Team Leader)</option>
                            <option value="icl"     {{ $user->role === 'icl'     ? 'selected' : '' }}>ICL (I Care Leader)</option>
                            <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-danger text-nowrap">
                            <i class="fa-solid fa-shield me-1"></i>Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Danger zone --}}
        <div class="card border-danger">
            <div class="card-header" style="background:#fff5f5">
                <h6 class="mb-0 fw-semibold text-danger" style="font-size:.82rem">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Zona Berbahaya
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2" style="font-size:.78rem">Hapus anggota ini beserta semua datanya. Tindakan ini tidak dapat dibatalkan.</p>
                <form id="del-member-form" method="POST" action="{{ route('members.destroy', $member) }}">
                    @csrf @method('DELETE')
                </form>
                <button class="btn btn-sm btn-outline-danger btn-delete w-100" data-form="del-member-form">
                    <i class="fa-solid fa-trash me-1"></i>Hapus Anggota
                </button>
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

function toggleNewAccount(show) {
    const fields = document.getElementById('new_account_fields');
    if (fields) fields.style.display = show ? '' : 'none';
}
</script>
@endpush
