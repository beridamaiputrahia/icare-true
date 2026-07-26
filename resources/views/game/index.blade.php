@extends('layouts.app')

@section('title', 'Game')

@push('styles')
<style>
#game-root { min-height: 80vh; }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <div id="game-root" style="max-width:480px;margin:0 auto;padding-bottom:120px;"></div>
</div>
@endsection

@push('scripts')
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
window.__GAME_MEMBERS__     = @json($members);
window.__GAME_LEADERBOARD__ = @json($leaderboard);
window.__GAME_USER__        = { id: {{ $user->id }}, nama: @json($user->name) };
window.__PUSHER_CONFIG__ = {
    key:     "{{ config('broadcasting.connections.pusher.key') }}",
    cluster: "{{ config('broadcasting.connections.pusher.options.cluster', 'ap1') }}"
};
</script>
<script src="{{ asset('js/game/GameFeature.js') }}"></script>
@endpush
