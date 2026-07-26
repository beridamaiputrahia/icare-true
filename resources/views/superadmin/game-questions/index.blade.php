@extends('layouts.app')

@section('title', 'Bank Soal Game')
@section('page-title', 'Bank Soal Game')
@section('breadcrumb')
    <li class="breadcrumb-item active">Bank Soal Game</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <ul class="nav nav-pills mb-0">
            @foreach($gameLabels as $type => $label)
                <li class="nav-item">
                    <a class="nav-link {{ $gameType === $type ? 'active' : '' }}"
                       href="{{ route('superadmin.game-questions.index', ['game_type' => $type]) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('superadmin.game-questions.create', ['game_type' => $gameType]) }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus me-1"></i>Tambah Soal
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success mb-0 rounded-0">{{ session('status') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Isi Soal</th>
                    <th>Ditambahkan oleh</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                <tr>
                    <td>
                        @switch($q->game_type)
                            @case('kuis')
                                <div class="fw-medium">{{ $q->data['q'] }}</div>
                                <div class="small text-muted">Jawaban benar: {{ $q->data['opsi'][$q->data['benar']] ?? '-' }}</div>
                                @break
                            @case('susun')
                                <div class="fw-medium">{{ $q->data['ref'] }}</div>
                                <div class="small text-muted">{{ $q->data['teks'] }}</div>
                                @break
                            @case('tebak')
                                <div class="fw-medium">{{ $q->data['jawaban'] }}</div>
                                <div class="small text-muted">{{ implode(' · ', $q->data['clues'] ?? []) }}</div>
                                @break
                            @case('memory')
                                <div class="fw-medium">{{ $q->data['a'] }}</div>
                                <div class="small text-muted">{{ $q->data['b'] }}</div>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $q->creator->name ?? '-' }}</td>
                    <td>
                        @if($q->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('superadmin.game-questions.toggle', $q) }}" class="d-inline">
                            @csrf @method('PUT')
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $q->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="fa-solid {{ $q->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('superadmin.game-questions.edit', $q) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('superadmin.game-questions.destroy', $q) }}" class="d-inline"
                              onsubmit="return confirm('Hapus soal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada soal tambahan untuk {{ $gameLabels[$gameType] }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($questions->hasPages())
        <div class="card-footer">{{ $questions->links() }}</div>
    @endif
</div>
@endsection
