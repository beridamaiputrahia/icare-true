@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item active">Kelola Pengguna</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-user-shield text-primary me-2"></i>Semua Pengguna (Lintas I Care Group)</h6>
        <a href="{{ route('superadmin.superadmins.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Pengguna
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>I Care Group</th>
                    <th>Role</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="fw-medium">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="badge bg-primary ms-1" style="font-size:.68rem">Anda</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->tenant?->nama_perusahaan ?: '—' }}</td>
                    <td>
                        <form method="POST" action="{{ route('superadmin.superadmins.role', $user) }}" class="d-flex align-items-center gap-1">
                            @csrf @method('PUT')
                            <select name="role" class="form-select form-select-sm" style="width:auto"
                                    onchange="this.form.submit()">
                                @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('superadmin.superadmins.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('superadmin.superadmins.destroy', $user) }}" class="d-inline"
                              onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
