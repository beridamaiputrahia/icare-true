@extends('layouts.app')

@section('title', 'Edit Tenant')
@section('page-title', 'Edit Tenant')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.tenants.index') }}">Kelola Tenant</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Tenant: {{ $tenant->nama_perusahaan }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.tenants.update', $tenant) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Komunitas <span class="text-danger">*</span></label>
                <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                       value="{{ old('nama_perusahaan', $tenant->nama_perusahaan) }}" required>
                @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" value="{{ $tenant->slug }}" disabled>
                <small class="text-muted">Slug tidak bisa diubah setelah dibuat.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $tenant->email) }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $tenant->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
