@extends('layouts.app')

@section('title', 'Edit Soal')
@section('page-title', 'Edit Soal — ' . $gameLabels[$question->game_type])
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.game-questions.index', ['game_type' => $question->game_type]) }}">Bank Soal Game</a></li>
    <li class="breadcrumb-item active">Edit Soal</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Edit Soal — {{ $gameLabels[$question->game_type] }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.game-questions.update', $question) }}">
            @csrf @method('PUT')
            @php $gameType = $question->game_type; @endphp
            @include('superadmin.game-questions._form')
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('superadmin.game-questions.index', ['game_type' => $gameType]) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
