@extends('layouts.app')

@section('title', 'Kelola Tenant')
@section('page-title', 'Kelola Tenant')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kelola Tenant</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-building text-primary me-2"></i>Daftar Tenant</h6>
        <a href="{{ route('superadmin.tenants.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Tenant
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Komunitas</th>
                    <th>Slug</th>
                    <th>Email</th>
                    <th>Jumlah User</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr>
                    <td class="fw-medium">{{ $tenant->nama_perusahaan }}</td>
                    <td><code>{{ $tenant->slug }}</code></td>
                    <td>{{ $tenant->email ?: '-' }}</td>
                    <td>{{ $tenant->users_count }}</td>
                    <td>
                        @if($tenant->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('superadmin.tenants.destroy', $tenant) }}" class="d-inline"
                              onsubmit="return confirm('Hapus tenant ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada tenant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
