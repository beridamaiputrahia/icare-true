@extends('layouts.app')

@section('title', $prayer->judul)
@section('page-title', 'Detail Doa')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('prayers.index') }}" class="text-decoration-none text-muted">Doa</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-body">
                {{-- Status Banner --}}
                @if($prayer->isAnswered())
                <div class="text-center p-4 mb-4 rounded-3" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0)">
                    <i class="fa-solid fa-check-double fa-2x text-success mb-2"></i>
                    <h6 class="fw-bold text-success mb-1">Puji Tuhan! Doa Telah Dijawab!</h6>
                    <p class="text-success mb-0" style="font-size:.8rem">{{ $prayer->answered_at?->translatedFormat('d F Y') }}</p>
                </div>
                @elseif($prayer->isApproved())
                <div class="text-center p-3 mb-4 rounded-3" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe)">
                    <i class="fa-solid fa-hands-praying fa-xl text-primary mb-2"></i>
                    <p class="text-primary mb-0 fw-semibold" style="font-size:.85rem">Doa ini sedang didoakan oleh komunitas</p>
                </div>
                @else
                <div class="d-flex align-items-center gap-2 p-3 mb-4 rounded-3 bg-warning bg-opacity-10">
                    <i class="fa-solid fa-clock text-warning"></i>
                    <p class="mb-0 text-warning" style="font-size:.82rem;font-weight:500">Menunggu persetujuan admin</p>
                </div>
                @endif

                <h5 class="fw-bold mb-1">{{ $prayer->judul }}</h5>
                <p class="text-muted mb-4" style="font-size:.78rem">
                    <i class="fa-solid fa-user me-1"></i>{{ $prayer->display_name }}
                    &nbsp;·&nbsp;
                    <i class="fa-regular fa-calendar me-1"></i>{{ $prayer->created_at->translatedFormat('d F Y') }}
                </p>

                <div class="p-4 rounded-3 mb-4" style="background:#f8fafc;border-left:4px solid #2563eb">
                    <p class="mb-0" style="line-height:1.9;font-size:.9rem;white-space:pre-line">{{ $prayer->isi_doa }}</p>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('prayers.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                    </a>

                    @if(auth()->user()->isAdmin())
                        @if($prayer->status === 'pending')
                        <form method="POST" action="{{ route('prayers.approve', $prayer) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check me-1"></i>Setujui
                            </button>
                        </form>
                        @elseif($prayer->status === 'approved')
                        <form method="POST" action="{{ route('prayers.answered', $prayer) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-check-double me-1"></i>Tandai Dijawab Tuhan
                            </button>
                        </form>
                        @endif
                        <form id="del-prayer" method="POST" action="{{ route('prayers.destroy', $prayer) }}" class="d-inline">
                            @csrf @method('DELETE')
                        </form>
                        <button class="btn btn-danger btn-sm btn-delete" data-form="del-prayer">
                            <i class="fa-solid fa-trash me-1"></i>Hapus
                        </button>
                    @elseif($prayer->user_id === auth()->id() && $prayer->status === 'pending')
                        <a href="{{ route('prayers.edit', $prayer) }}" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-pen me-1"></i>Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
