@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.superadmins.index') }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Tambah Pengguna Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.superadmins.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required
                        onchange="document.getElementById('tenant_wrap').style.display = this.value === 'superadmin' ? 'none' : ''">
                    <option value="">— Pilih Role —</option>
                    @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4" id="tenant_wrap" style="{{ old('role') === 'superadmin' ? 'display:none' : '' }}">
                <label class="form-label">Tenant <span class="text-danger">*</span></label>
                <select name="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror">
                    <option value="">— Pilih Tenant —</option>
                    @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ (string) old('tenant_id') === (string) $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->nama_perusahaan }}
                    </option>
                    @endforeach
                </select>
                @error('tenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Tidak berlaku untuk role Super Admin (tidak terikat tenant manapun).</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Kata Sandi <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 karakter" required autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Ulangi kata sandi" required autocomplete="new-password">
            </div>

            <div class="alert alert-warning py-2 px-3 mb-4" style="font-size:.83rem">
                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                Akun dengan role Super Admin punya akses penuh ke SEMUA data di semua tenant. Berikan hanya kepada
                orang yang benar-benar terpercaya.
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('superadmin.superadmins.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
