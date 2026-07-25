@extends('layouts.app')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')
@section('breadcrumb')
    <li class="breadcrumb-item active">Notifikasi</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        {{-- Push Notification Opt-in --}}
        <div class="card mb-3" id="pushOptInCard" style="display:none">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-15 flex-shrink-0"
                     style="width:44px;height:44px">
                    <i class="fa-solid fa-mobile-screen-button text-primary"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0 fw-semibold" style="font-size:.875rem">Aktifkan Notifikasi HP</p>
                    <p class="mb-0 text-muted" style="font-size:.8rem">Dapatkan pemberitahuan langsung di HP saat ada foto, pengumuman, atau ayat harian baru — walau aplikasi tertutup.</p>
                </div>
                <button type="button" class="btn btn-primary btn-sm flex-shrink-0" id="pushOptInBtn">
                    Aktifkan
                </button>
            </div>
        </div>

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

@push('scripts')
<script>
(function () {
    if (!('Notification' in window)) return;

    const card = document.getElementById('pushOptInCard');
    const btn  = document.getElementById('pushOptInBtn');

    // Tampilkan kartu hanya kalau belum diberi izin/ditolak permanen
    if (Notification.permission === 'default') {
        card.style.display = '';
    }

    btn?.addEventListener('click', async () => {
        btn.disabled = true;
        btn.textContent = 'Memproses...';

        const ok = await window.icareEnablePushNotifications();

        if (ok) {
            card.style.display = 'none';
        } else {
            btn.disabled = false;
            btn.textContent = 'Aktifkan';
            if (Notification.permission === 'denied') {
                card.style.display = 'none';
            }
        }
    });
})();
</script>
@endpush
