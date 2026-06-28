@extends('layouts.app')

@section('title', $devotion->judul)
@section('page-title', 'Detail Renungan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('devotions.index') }}" class="text-decoration-none text-muted">Renungan</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            @if($devotion->gambar)
            <img src="{{ Storage::url($devotion->gambar) }}" class="card-img-top" style="max-height:280px;object-fit:cover">
            @endif
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $devotion->judul }}</h4>
                        <p class="text-muted small mb-0">
                            <i class="fa-solid fa-user me-1"></i>{{ $devotion->user->name ?? 'Anonim' }}
                            &nbsp;·&nbsp;{{ $devotion->created_at->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    <span class="badge badge-{{ $devotion->status }} flex-shrink-0">
                        @switch($devotion->status)
                            @case('pending')  Menunggu  @break
                            @case('approved') Disetujui @break
                            @case('rejected') Ditolak   @break
                        @endswitch
                    </span>
                </div>

                @if($devotion->ayat_pendukung)
                <div class="p-3 rounded-3 mb-4" style="background:#f0f9ff;border-left:4px solid #0ea5e9">
                    <i class="fa-solid fa-bible text-info me-2"></i>
                    <strong style="font-size:.875rem">{{ $devotion->ayat_pendukung }}</strong>
                </div>
                @endif

                @if($devotion->status === 'rejected' && $devotion->catatan_admin)
                <div class="alert alert-danger d-flex gap-2 mb-4" style="font-size:.82rem">
                    <i class="fa-solid fa-circle-xmark mt-1 flex-shrink-0"></i>
                    <div><strong>Catatan Admin:</strong> {{ $devotion->catatan_admin }}</div>
                </div>
                @endif

                <div class="devotion-content" style="line-height:1.9;font-size:.9rem;white-space:pre-line">{{ $devotion->isi }}</div>

                @if($devotion->isApproved())
                <div class="mt-3 text-muted" style="font-size:.78rem">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>
                    Disetujui oleh {{ $devotion->approvedBy->name ?? 'Admin' }} pada {{ $devotion->approved_at?->translatedFormat('d F Y') }}
                </div>
                @endif

                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('devotions.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                    </a>
                    @if($devotion->status === 'approved')
                    <a href="{{ route('export.devotion.pdf', $devotion) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                        <i class="fa-solid fa-file-pdf me-1"></i>Export PDF
                    </a>
                    @endif
                    @if($devotion->user_id === auth()->id() && $devotion->status !== 'approved')
                    <a href="{{ route('devotions.edit', $devotion) }}" class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-pen me-1"></i>Edit
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        @if($devotion->status === 'pending')
                        <form method="POST" action="{{ route('devotions.approve', $devotion) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check me-1"></i>Setujui
                            </button>
                        </form>
                        <button class="btn btn-danger btn-sm" onclick="rejectDevotion({{ $devotion->id }})">
                            <i class="fa-solid fa-xmark me-1"></i>Tolak
                        </button>
                        @endif
                    <form id="del-devotion" method="POST" action="{{ route('devotions.destroy', $devotion) }}" class="d-inline">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-danger btn-sm btn-delete" data-form="del-devotion">
                        <i class="fa-solid fa-trash me-1"></i>Hapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="catatan_admin" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Tolak Renungan</button>
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
