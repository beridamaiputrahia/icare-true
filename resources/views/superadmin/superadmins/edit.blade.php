@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.superadmins.index') }}">Kelola Pengguna</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Pengguna: {{ $user->name }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.superadmins.update', $user) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required
                        onchange="document.getElementById('tenant_wrap').style.display = this.value === 'superadmin' ? 'none' : ''">
                    @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4" id="tenant_wrap" style="{{ old('role', $user->role) === 'superadmin' ? 'display:none' : '' }}">
                <label class="form-label">I Care Group <span class="text-danger">*</span></label>
                <select name="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror">
                    <option value="">— Pilih I Care Group —</option>
                    @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ (string) old('tenant_id', $user->tenant_id) === (string) $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->nama_perusahaan }}
                    </option>
                    @endforeach
                </select>
                @error('tenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Tidak berlaku untuk role Super Admin (tidak terikat I Care Group manapun).</small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('superadmin.superadmins.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
