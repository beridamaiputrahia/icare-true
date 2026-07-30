@extends('layouts.app')

@section('title', 'Scan Absensi — ' . $schedule->nama_kegiatan)
@section('page-title', 'Scan Absensi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}" class="text-decoration-none text-muted">Jadwal</a></li>
    <li class="breadcrumb-item"><a href="{{ route('schedules.show', $schedule) }}" class="text-decoration-none text-muted">{{ $schedule->nama_kegiatan }}</a></li>
    <li class="breadcrumb-item active">Scan Absensi</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-qrcode text-primary me-2"></i>{{ $schedule->nama_kegiatan }}</h6>
                <small class="text-muted">Arahkan kamera ke QR Code kartu anggota</small>
            </div>
            <div class="card-body">
                <div id="qr-reader" style="width:100%"></div>
                <div id="scan-result" class="mt-3"></div>

                <hr class="my-3">
                <h6 class="fw-semibold text-muted mb-2" style="font-size:.8rem;text-transform:uppercase">Terakhir Discan</h6>
                <ul id="scan-log" class="list-group list-group-flush" style="max-height:220px;overflow-y:auto"></ul>

                <div class="mt-3">
                    <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    'use strict';

    const resultBox = document.getElementById('scan-result');
    const logList    = document.getElementById('scan-log');
    const storeUrl   = @json(route('attendances.store', $schedule));
    const csrfToken  = @json(csrf_token());

    let busy = false;

    function showResult(status, message) {
        const cls = status === 'success' ? 'alert-success' : (status === 'duplicate' ? 'alert-warning' : 'alert-danger');
        resultBox.innerHTML = `<div class="alert ${cls} mb-0">${message}</div>`;
    }

    function addLog(message) {
        const li = document.createElement('li');
        li.className = 'list-group-item small';
        li.textContent = new Date().toLocaleTimeString('id-ID') + ' — ' + message;
        logList.prepend(li);
    }

    async function onScanSuccess(decodedText) {
        if (busy) return;

        let payload;
        try {
            payload = JSON.parse(decodedText);
        } catch (e) {
            showResult('error', 'QR Code tidak valid.');
            return;
        }

        if (!payload.id) {
            showResult('error', 'QR Code tidak dikenali sebagai kartu anggota.');
            return;
        }

        busy = true;
        try {
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ member_id: payload.id }),
            });
            const data = await res.json();
            showResult(data.status, data.message);
            addLog(data.message);
        } catch (e) {
            showResult('error', 'Gagal menghubungi server.');
        } finally {
            setTimeout(() => { busy = false; }, 1500);
        }
    }

    const html5QrCode = new Html5Qrcode('qr-reader');
    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess,
        () => {}
    ).catch(() => {
        showResult('error', 'Tidak dapat mengakses kamera. Periksa izin kamera browser.');
    });

    window.addEventListener('beforeunload', () => {
        html5QrCode.stop().catch(() => {});
    });
})();
</script>
@endpush
