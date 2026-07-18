@extends('layouts.app')

@section('title', 'Tambah Tenant')
@section('page-title', 'Tambah Tenant')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.tenants.index') }}">Kelola Tenant</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Tambah Tenant Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.tenants.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Komunitas <span class="text-danger">*</span></label>
                <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                       value="{{ old('nama_perusahaan') }}" required>
                @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
