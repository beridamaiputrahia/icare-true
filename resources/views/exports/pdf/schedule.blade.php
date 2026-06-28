<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:12px; color:#1e293b; background:#fff; }
.header { background:#2563eb; color:#fff; padding:24px 32px; margin-bottom:24px; }
.header h1 { font-size:22px; font-weight:700; margin-bottom:4px; }
.header p  { font-size:11px; opacity:.85; }
.content { padding:0 32px 32px; }
.label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#94a3b8; margin-bottom:4px; }
.value { font-size:13px; color:#1e293b; margin-bottom:16px; }
.badge { display:inline-block; padding:4px 10px; border-radius:12px; font-size:10px; font-weight:600; }
.badge-upcoming { background:#dbeafe; color:#1e40af; }
.badge-ongoing  { background:#d1fae5; color:#065f46; }
.badge-done     { background:#f1f5f9; color:#475569; }
.divider { border:none; border-top:1px solid #e2e8f0; margin:16px 0; }
.footer { margin-top:32px; font-size:9px; color:#94a3b8; text-align:center; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $schedule->nama_kegiatan }}</h1>
    <p>Jadwal Kegiatan — I Care True Community</p>
</div>
<div class="content">
    <div class="label">Status</div>
    <div class="value">
        <span class="badge badge-{{ $schedule->status }}">
            @if($schedule->status==='upcoming') Akan Datang
            @elseif($schedule->status==='ongoing') Berlangsung
            @else Selesai @endif
        </span>
    </div>

    <div class="label">Tanggal &amp; Waktu</div>
    <div class="value">{{ $schedule->tanggal->translatedFormat('l, d F Y') }} pukul {{ $schedule->formatted_time }} WIB</div>

    <div class="label">Lokasi</div>
    <div class="value">{{ $schedule->lokasi }}</div>

    @if($schedule->pembicara)
    <div class="label">Pembicara / Penanggung Jawab</div>
    <div class="value">{{ $schedule->pembicara }}</div>
    @endif

    @if($schedule->deskripsi)
    <hr class="divider">
    <div class="label">Deskripsi</div>
    <div class="value" style="line-height:1.7">{{ $schedule->deskripsi }}</div>
    @endif
</div>
<div class="footer">Diekspor pada {{ now()->translatedFormat('d F Y, H:i') }} WIB — I Care True &copy; {{ date('Y') }}</div>
</body>
</html>
