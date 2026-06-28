<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:11px; color:#1e293b; }
.header { background:#2563eb; color:#fff; padding:20px 28px; margin-bottom:20px; }
.header h1 { font-size:18px; font-weight:700; }
.header p  { font-size:10px; opacity:.8; }
table { width:100%; border-collapse:collapse; }
thead tr { background:#f8fafc; }
th { padding:8px 12px; text-align:left; font-size:9px; font-weight:700; text-transform:uppercase;
     letter-spacing:.05em; color:#64748b; border-bottom:2px solid #e2e8f0; }
td { padding:9px 12px; border-bottom:1px solid #f1f5f9; vertical-align:top; }
tr:nth-child(even) td { background:#fafafa; }
.badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:9px; font-weight:600; }
.badge-upcoming { background:#dbeafe; color:#1e40af; }
.badge-ongoing  { background:#d1fae5; color:#065f46; }
.badge-done     { background:#f1f5f9; color:#475569; }
.footer { margin-top:20px; font-size:8px; color:#94a3b8; text-align:center; }
</style>
</head>
<body>
<div class="header">
    <h1>Daftar Jadwal Kegiatan</h1>
    <p>I Care True Community — {{ now()->translatedFormat('d F Y') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Lokasi</th>
            <th>Pembicara</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($schedules as $i => $s)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><strong>{{ $s->nama_kegiatan }}</strong></td>
            <td>{{ $s->tanggal->translatedFormat('d M Y') }}</td>
            <td>{{ $s->formatted_time }}</td>
            <td>{{ $s->lokasi }}</td>
            <td>{{ $s->pembicara ?? '-' }}</td>
            <td>
                <span class="badge badge-{{ $s->status }}">
                    @if($s->status==='upcoming') Akan Datang
                    @elseif($s->status==='ongoing') Berlangsung
                    @else Selesai @endif
                </span>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:20px;color:#94a3b8">Belum ada jadwal</td></tr>
        @endforelse
    </tbody>
</table>
<div class="footer">Total {{ $schedules->count() }} jadwal — Diekspor {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
</body>
</html>
