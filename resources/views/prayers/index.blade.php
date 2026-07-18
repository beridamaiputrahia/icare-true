@extends('layouts.app')

@section('title', 'Doa')
@section('page-title', 'Doa Komunitas')
@section('breadcrumb')
    <li class="breadcrumb-item active">Doa</li>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card text-center py-3">
            <div class="stat-value text-warning">{{ $stats['pending'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card text-center py-3">
            <div class="stat-value text-primary">{{ $stats['approved'] }}</div>
            <div class="stat-label">Aktif Didoakan</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card text-center py-3">
            <div class="stat-value text-success">{{ $stats['answered'] }}</div>
            <div class="stat-label">Dijawab Tuhan</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-hands-praying text-primary me-2"></i>Daftar Doa</h6>
        <a href="{{ route('prayers.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Kirim Doa
        </a>
    </div>
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group input-group-sm" style="max-width:240px">
                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="max-width:140px">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}>Menunggu</option>
                <option value="approved" {{ request('status')=='approved' ? 'selected':'' }}>Aktif</option>
                <option value="answered" {{ request('status')=='answered' ? 'selected':'' }}>Dijawab</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('prayers.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            @endif
        </form>
    </div>

    <div class="card-body p-0">
        @forelse($prayers as $prayer)
        <div class="d-flex gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
            {{-- Status Icon --}}
            <div class="flex-shrink-0 mt-1">
                @php
                    $iconData = match($prayer->status) {
                        'answered' => ['bg-success', 'fa-check-double', 'Dijawab Tuhan!'],
                        'approved' => ['bg-primary', 'fa-hands-praying', 'Aktif Didoakan'],
                        default    => ['bg-warning', 'fa-clock', 'Menunggu Persetujuan'],
                    };
                @endphp
                <div class="rounded-circle d-flex align-items-center justify-content-center {{ $iconData[0] }} bg-opacity-15"
                     style="width:40px;height:40px" title="{{ $iconData[2] }}">
                    <i class="fa-solid {{ $iconData[1] }} {{ str_replace('bg-', 'text-', $iconData[0]) }}"></i>
                </div>
            </div>

            {{-- Content --}}
            <div class="flex-grow-1 overflow-hidden">
                <div class="d-flex align-items-start justify-content-between gap-2">
                    <div class="overflow-hidden">
                        <p class="mb-0 fw-semibold text-truncate" style="font-size:.875rem">{{ $prayer->judul }}</p>
                        <p class="mb-1 text-muted" style="font-size:.75rem">
                            <i class="fa-solid fa-user me-1"></i>{{ $prayer->display_name }}
                            &nbsp;·&nbsp;{{ $prayer->created_at->diffForHumans() }}
                        </p>
                        <p class="mb-0 text-muted" style="font-size:.82rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                            {{ $prayer->isi_doa }}
                        </p>
                    </div>
                    <div class="flex-shrink-0 d-flex flex-column align-items-end gap-1">
                        <span class="badge badge-{{ $prayer->status }}">
                            @switch($prayer->status)
                                @case('pending')  Menunggu  @break
                                @case('approved') Aktif     @break
                                @case('answered') Dijawab!  @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex-shrink-0 d-flex flex-column gap-1 justify-content-center">
                <a href="{{ route('prayers.show', $prayer) }}" class="btn btn-sm btn-outline-info" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                @if($_feat['doa'] ?? false)
                    @if($prayer->status === 'pending')
                    <form method="POST" action="{{ route('prayers.approve', $prayer) }}" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-success" title="Setujui"><i class="fa-solid fa-check"></i></button>
                    </form>
                    @elseif($prayer->status === 'approved')
                    <form method="POST" action="{{ route('prayers.answered', $prayer) }}" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-warning" title="Tandai Dijawab"><i class="fa-solid fa-check-double"></i></button>
                    </form>
                    @endif
                    <form id="del-pray-{{ $prayer->id }}" method="POST" action="{{ route('prayers.destroy', $prayer) }}">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-pray-{{ $prayer->id }}" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                @elseif($prayer->user_id === auth()->id() && $prayer->status === 'pending')
                    <a href="{{ route('prayers.edit', $prayer) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form id="del-pray-{{ $prayer->id }}" method="POST" action="{{ route('prayers.destroy', $prayer) }}">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-pray-{{ $prayer->id }}" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-hands-praying fa-2x mb-2 d-block opacity-25"></i>
            <p class="mb-2">Belum ada doa</p>
            <a href="{{ route('prayers.create') }}" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-plus me-1"></i>Kirim Doa Pertama
            </a>
        </div>
        @endforelse
    </div>

    @if($prayers->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between">
        <small class="text-muted">{{ $prayers->total() }} doa</small>
        {{ $prayers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
