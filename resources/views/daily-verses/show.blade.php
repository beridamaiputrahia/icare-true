@extends('layouts.app')

@section('title', $dailyVerse->referensi)
@section('page-title', 'Detail Ayat Harian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('daily-verses.index') }}" class="text-decoration-none text-muted">Ayat Harian</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="verse-card mb-3">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-bible" style="opacity:.7"></i>
                <span style="font-size:.72rem;opacity:.7;text-transform:uppercase;letter-spacing:.08em;font-weight:600">Ayat Harian</span>
                @if($dailyVerse->tanggal)
                <span style="font-size:.72rem;opacity:.7;margin-left:auto">{{ $dailyVerse->tanggal->translatedFormat('d F Y') }}</span>
                @endif
            </div>
            <p class="verse-text mb-3">"{{ $dailyVerse->ayat }}"</p>
            <p class="verse-ref mb-0">— {{ $dailyVerse->referensi }}</p>
        </div>

        @if($dailyVerse->renungan_singkat)
        <div class="card">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="fa-solid fa-lightbulb text-warning me-2"></i>Renungan Singkat</h6>
                <p class="mb-0" style="line-height:1.85;font-size:.9rem">{{ $dailyVerse->renungan_singkat }}</p>
            </div>
        </div>
        @endif

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('daily-verses.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('daily-verses.edit', $dailyVerse) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen me-1"></i>Edit
            </a>
            <form id="del-verse" method="POST" action="{{ route('daily-verses.destroy', $dailyVerse) }}" class="d-inline">
                @csrf @method('DELETE')
            </form>
            <button class="btn btn-danger btn-sm btn-delete" data-form="del-verse">
                <i class="fa-solid fa-trash me-1"></i>Hapus
            </button>
        </div>
    </div>
</div>
@endsection
