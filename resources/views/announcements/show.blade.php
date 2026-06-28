@extends('layouts.app')

@section('title', $announcement->judul)
@section('page-title', 'Detail Pengumuman')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted">Pengumuman</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            @if($announcement->gambar)
            <img src="{{ Storage::url($announcement->gambar) }}" class="card-img-top" style="max-height:320px;object-fit:cover" alt="{{ $announcement->judul }}">
            @endif
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $announcement->judul }}</h4>
                        <p class="text-muted small mb-0">
                            <i class="fa-solid fa-user me-1"></i>{{ $announcement->penulis }}
                            &nbsp;·&nbsp;
                            <i class="fa-regular fa-calendar me-1"></i>{{ $announcement->created_at->translatedFormat('d F Y') }}
                            &nbsp;·&nbsp;{{ $announcement->created_at->diffForHumans() }}
                        </p>
                    </div>
                    @if(auth()->user()->isAdmin())
                    <span class="badge {{ $announcement->is_published ? 'bg-success' : 'bg-secondary' }} flex-shrink-0">
                        {{ $announcement->is_published ? 'Published' : 'Draft' }}
                    </span>
                    @endif
                </div>
                <hr>
                <div class="announcement-content" style="line-height:1.85;font-size:.9rem">
                    {!! $announcement->isi !!}
                </div>
                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                    </a>
                    <a href="{{ route('export.announcement.pdf', $announcement) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                        <i class="fa-solid fa-file-pdf me-1"></i>Export PDF
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-pen me-1"></i>Edit
                    </a>
                    <form id="del-ann" method="POST" action="{{ route('announcements.destroy', $announcement) }}" class="d-inline">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-danger btn-sm btn-delete" data-form="del-ann">
                        <i class="fa-solid fa-trash me-1"></i>Hapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
