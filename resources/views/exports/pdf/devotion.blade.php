<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:12px; color:#1e293b; }
.header { background:#7c3aed; color:#fff; padding:24px 32px; margin-bottom:24px; }
.header h1 { font-size:20px; font-weight:700; margin-bottom:4px; }
.header p  { font-size:10px; opacity:.8; }
.content { padding:0 32px 32px; }
.verse-box { background:#f8f5ff; border-left:4px solid #7c3aed; padding:14px 18px; margin-bottom:20px; border-radius:4px; }
.verse-box p { font-style:italic; color:#4c1d95; line-height:1.7; }
.meta { color:#64748b; font-size:10px; margin-bottom:20px; }
.body { line-height:1.9; font-size:12px; }
.footer { margin-top:32px; font-size:9px; color:#94a3b8; text-align:center; }
</style>
</head>
<body>
<div class="header">
    <h1>{{ $devotion->judul }}</h1>
    <p>Renungan — I Care True Community</p>
</div>
<div class="content">
    <div class="meta">
        Oleh: <strong>{{ $devotion->user->name }}</strong> &nbsp;·&nbsp;
        {{ $devotion->created_at->translatedFormat('d F Y') }}
    </div>
    @if($devotion->ayat_pendukung)
    <div class="verse-box">
        <p>{{ $devotion->ayat_pendukung }}</p>
    </div>
    @endif
    <div class="body">{{ strip_tags($devotion->isi) }}</div>
</div>
<div class="footer">Diekspor {{ now()->translatedFormat('d F Y, H:i') }} WIB — I Care True &copy; {{ date('Y') }}</div>
</body>
</html>
