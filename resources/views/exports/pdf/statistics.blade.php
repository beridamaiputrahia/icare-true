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
.kpi-grid { display:table; width:100%; margin-bottom:20px; }
.kpi-item { display:table-cell; text-align:center; border:1px solid #e2e8f0; padding:16px; }
.kpi-val { font-size:28px; font-weight:800; color:#2563eb; }
.kpi-lbl { font-size:9px; color:#64748b; text-transform:uppercase; letter-spacing:.04em; }
.section-title { font-size:13px; font-weight:700; margin:20px 0 10px; padding-bottom:4px;
                 border-bottom:2px solid #2563eb; color:#2563eb; }
table { width:100%; border-collapse:collapse; margin-bottom:16px; }
thead tr { background:#f8fafc; }
th { padding:7px 10px; font-size:9px; font-weight:700; text-transform:uppercase; color:#64748b;
     border-bottom:1px solid #e2e8f0; text-align:left; }
td { padding:8px 10px; border-bottom:1px solid #f1f5f9; }
tr:nth-child(even) td { background:#fafafa; }
.footer { margin-top:20px; font-size:8px; color:#94a3b8; text-align:center; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Statistik Komunitas</h1>
    <p>I Care True — Diekspor {{ $generatedAt }} WIB</p>
</div>

<div class="kpi-grid">
    <div class="kpi-item"><div class="kpi-val">{{ $totalMembers }}</div><div class="kpi-lbl">Total Anggota</div></div>
    <div class="kpi-item"><div class="kpi-val">{{ $totalDevotions }}</div><div class="kpi-lbl">Renungan Disetujui</div></div>
    <div class="kpi-item"><div class="kpi-val">{{ $totalPrayers }}</div><div class="kpi-lbl">Total Doa</div></div>
</div>

<div class="section-title">Top Penulis Renungan</div>
<table>
    <thead><tr><th>#</th><th>Nama</th><th>Renungan Disetujui</th></tr></thead>
    <tbody>
        @foreach($topWriters as $i => $writer)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $writer->name }}</td>
            <td><strong>{{ $writer->devotions_count }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">I Care True Community &copy; {{ date('Y') }} — Laporan ini dibuat secara otomatis oleh sistem.</div>
</body>
</html>
