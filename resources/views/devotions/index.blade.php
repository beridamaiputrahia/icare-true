@extends('layouts.app')

@section('title', 'Renungan')
@section('page-title', 'Renungan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Renungan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-book-open-reader text-warning me-2"></i>Daftar Renungan</h6>
        <a href="{{ route('devotions.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Upload Renungan
        </a>
    </div>
    <div class="card-body border-bottom py-2">
        <form method="GET" class="d-flex gap-2 flex-wrap">
            <div class="input-group input-group-sm" style="max-width:260px">
                <span class="input-group-text"><i class="fa-solid fa-search text-muted"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-select form-select-sm" style="max-width:130px">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status')=='pending'  ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('devotions.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Ayat Pendukung</th>
                    @if(auth()->user()->isAdmin())<th>Penulis</th>@endif
                    <th>Status</th>
                    <th>Dikirim</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devotions as $devotion)
                <tr>
                    <td class="text-muted" style="font-size:.8rem">{{ $devotions->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="fw-semibold text-truncate" style="font-size:.875rem;max-width:200px">{{ $devotion->judul }}</div>
                    </td>
                    <td style="font-size:.82rem">{{ $devotion->ayat_pendukung ?? '-' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-placeholder" style="width:28px;height:28px;font-size:.7rem;background:#dbeafe;color:#1e40af">
                                {{ strtoupper(substr($devotion->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <span style="font-size:.82rem">{{ $devotion->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    @endif
                    <td>
                        <span class="badge badge-{{ $devotion->status }}">
                            @switch($devotion->status)
                                @case('pending')  Menunggu  @break
                                @case('approved') Disetujui @break
                                @case('rejected') Ditolak   @break
                            @endswitch
                        </span>
                    </td>
                    <td class="text-muted" style="font-size:.78rem">{{ $devotion->created_at->diffForHumans() }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('devotions.show', $devotion) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if($devotion->user_id === auth()->id() && $devotion->status !== 'approved')
                            <a href="{{ route('devotions.edit', $devotion) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                @if($devotion->status === 'pending')
                                <form method="POST" action="{{ route('devotions.approve', $devotion) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" title="Setujui">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-outline-danger" title="Tolak"
                                        onclick="rejectDevotion({{ $devotion->id }})">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                @endif
                            <form id="del-dev-{{ $devotion->id }}" method="POST" action="{{ route('devotions.destroy', $devotion) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-dev-{{ $devotion->id }}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            @elseif($devotion->user_id === auth()->id())
                            <form id="del-dev-{{ $devotion->id }}" method="POST" action="{{ route('devotions.destroy', $devotion) }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger btn-delete" data-form="del-dev-{{ $devotion->id }}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-book-open fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada renungan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($devotions->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between">
        <small class="text-muted">{{ $devotions->total() }} renungan</small>
        {{ $devotions->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Reject Modal --}}
@if(auth()->user()->isAdmin())
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="rejectForm" method="POST">
                @csrf @method('PATCH')
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-semibold">Tolak Renungan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Catatan / Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Berikan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-xmark me-1"></i>Tolak Renungan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function rejectDevotion(id) {
    document.getElementById('rejectForm').action = '/devotions/' + id + '/reject';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush
