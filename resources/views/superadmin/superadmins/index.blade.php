@extends('layouts.app')

@section('title', 'Kelola Superadmin')
@section('page-title', 'Kelola Superadmin')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kelola Superadmin</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user-shield text-primary me-2"></i>Daftar Superadmin</h6>
        <a href="{{ route('superadmin.superadmins.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Superadmin
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Terdaftar Sejak</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($superadmins as $superadmin)
                <tr>
                    <td class="fw-medium">
                        {{ $superadmin->name }}
                        @if($superadmin->id === auth()->id())
                            <span class="badge bg-primary ms-1" style="font-size:.68rem">Anda</span>
                        @endif
                    </td>
                    <td>{{ $superadmin->email }}</td>
                    <td>{{ $superadmin->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        @if($superadmin->id !== auth()->id())
                        <form method="POST" action="{{ route('superadmin.superadmins.destroy', $superadmin) }}" class="d-inline"
                              onsubmit="return confirm('Hapus akun superadmin ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada superadmin.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
