@extends('layouts.app')

@section('title', 'Tambah Soal')
@section('page-title', 'Tambah Soal — ' . $gameLabels[$gameType])
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.game-questions.index', ['game_type' => $gameType]) }}">Bank Soal Game</a></li>
    <li class="breadcrumb-item active">Tambah Soal</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Tambah Soal — {{ $gameLabels[$gameType] }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.game-questions.store') }}">
            @csrf
            <input type="hidden" name="game_type" value="{{ $gameType }}">
            @php $question = null; @endphp
            @include('superadmin.game-questions._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Soal</button>
                <a href="{{ route('superadmin.game-questions.index', ['game_type' => $gameType]) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
