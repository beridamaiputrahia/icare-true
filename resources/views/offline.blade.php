<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Offline — I Care True</title>
    <style>
        * { margin:0;padding:0;box-sizing:border-box; }
        body {
            font-family:'Segoe UI',sans-serif;
            min-height:100vh;
            background:linear-gradient(135deg,#1e293b 0%,#0f172a 100%);
            display:flex;align-items:center;justify-content:center;
        }
        .card {
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            border-radius:20px;padding:3rem 2.5rem;
            max-width:420px;text-align:center;
        }
        h1 { color:#fff;font-size:1.5rem;font-weight:700;margin-bottom:.75rem; }
        p  { color:#94a3b8;font-size:.9rem;line-height:1.7;margin-bottom:1.5rem; }
        .emoji { font-size:3.5rem;margin-bottom:1.5rem;display:block; }
        .btn {
            background:#2563eb;color:#fff;border:none;
            padding:.6rem 1.5rem;border-radius:8px;cursor:pointer;
            font-size:.9rem;font-weight:600;
        }
        .btn:hover { background:#1d4ed8; }
    </style>
</head>
<body>
    <div class="card">
        <span class="emoji">📵</span>
        <h1>Tidak Ada Koneksi</h1>
        <p>Anda sedang offline. Periksa koneksi internet Anda dan coba lagi.</p>
        <button class="btn" onclick="window.location.reload()">Coba Lagi</button>
    </div>
</body>
</html>
