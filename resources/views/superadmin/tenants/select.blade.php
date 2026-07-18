@extends('layouts.app')

@section('title', 'Pilih Tenant')
@section('page-title', 'Pilih Tenant')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pilih Tenant</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">
            <i class="fa-solid fa-right-left text-primary me-2"></i>Masuk Sebagai Tenant
        </h6>
    </div>
    <div class="card-body">
        <p class="text-muted" style="font-size:.875rem">
            Sebagai Super Admin, pilih salah satu tenant untuk mengakses data (jadwal, anggota, renungan, dll)
            milik tenant tersebut. Anda bisa berpindah tenant kapan saja lewat menu "Ganti Tenant".
        </p>

        @if(session('active_tenant_id'))
        <div class="alert alert-info py-2 px-3" style="font-size:.85rem">
            Saat ini Anda sedang masuk sebagai:
            <strong>{{ \App\Models\Tenant::find(session('active_tenant_id'))?->nama_perusahaan }}</strong>
        </div>
        @endif

        <div class="row g-3 mt-1">
            @forelse($tenants as $tenant)
            <div class="col-12 col-md-6 col-lg-4">
                <form method="POST" action="{{ route('superadmin.tenants.switch') }}">
                    @csrf
                    <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                    <button type="submit" class="btn btn-outline-primary w-100 text-start p-3 h-100">
                        <div class="fw-semibold">{{ $tenant->nama_perusahaan }}</div>
                        <div class="text-muted" style="font-size:.78rem">{{ $tenant->slug }}</div>
                    </button>
                </form>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-4">Belum ada tenant aktif.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
