<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:12px; color:#1e293b; }
.header { background:#2563eb; color:#fff; padding:24px 32px; margin-bottom:24px; }
.header h1 { font-size:20px; font-weight:700; margin-bottom:4px; }
.header p  { font-size:10px; opacity:.8; }
.content { padding:0 32px 32px; }
.meta { color:#64748b; font-size:10px; margin-bottom:20px; }
.body { line-height:1.8; font-size:12px; white-space:pre-wrap; }
.footer { margin-top:32px; font-size:9px; color:#94a3b8; text-align:center; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $announcement->judul }}</h1>
    <p>Pengumuman — I Care True Community</p>
</div>
<div class="content">
    <div class="meta">
        Oleh: <strong>{{ $announcement->penulis }}</strong> &nbsp;·&nbsp;
        {{ $announcement->created_at->translatedFormat('d F Y') }}
    </div>
    <div class="body">{{ strip_tags($announcement->isi) }}</div>
</div>
<div class="footer">Diekspor {{ now()->translatedFormat('d F Y, H:i') }} WIB — I Care True &copy; {{ date('Y') }}</div>
</body>
</html>
