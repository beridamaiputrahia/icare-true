@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('breadcrumb')
    <li class="breadcrumb-item active">Notifikasi</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-bell text-warning me-2"></i>Semua Notifikasi</h6>
                @if($notifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-check-double me-1"></i>Tandai Semua Dibaca
                    </button>
                </form>
                @endif
            </div>
            <div class="card-body p-0">
                @forelse($notifications as $notif)
                @php $data = $notif->data; $isRead = !is_null($notif->read_at); @endphp
                <div class="d-flex gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }} {{ $isRead ? '' : 'bg-primary bg-opacity-5' }}">
                    <div class="flex-shrink-0 mt-1">
                        @php $color = $data['color'] ?? 'primary'; $icon = $data['icon'] ?? 'fa-bell'; @endphp
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-{{ $color }} bg-opacity-15"
                             style="width:40px;height:40px">
                            <i class="fa-solid {{ $icon }} text-{{ $color }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0 fw-{{ $isRead ? 'normal' : 'semibold' }}" style="font-size:.875rem">
                            {{ $data['title'] ?? 'Notifikasi' }}
                        </p>
                        <p class="mb-1 text-muted" style="font-size:.8rem">{{ $data['message'] ?? '' }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem">
                            <i class="fa-regular fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                            @if(!$isRead)<span class="badge bg-primary ms-2" style="font-size:.6rem">Baru</span>@endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 d-flex flex-column gap-1 justify-content-center">
                        @if(isset($data['url']))
                        <a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-regular fa-bell-slash fa-2x mb-3 d-block opacity-25"></i>
                    <p class="mb-0" style="font-size:.85rem">Tidak ada notifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
