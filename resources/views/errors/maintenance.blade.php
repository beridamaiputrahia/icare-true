<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance — I Care True</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Font Awesome self-hosted lewat Vite -- lihat catatan di layouts/app.blade.php --}}
    @vite('resources/css/fontawesome.css')
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .maintenance-card {
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            max-width: 520px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .icon-wrap {
            width: 90px; height: 90px; border-radius: 50%;
            background: rgba(251,191,36,.15);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(251,191,36,.3); }
            50%       { box-shadow: 0 0 0 16px rgba(251,191,36,0); }
        }
        .gear-spin { animation: spin 4s linear infinite; display: inline-block; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .eta-badge {
            display: inline-block;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 20px;
            padding: .4rem 1rem;
            font-size: .8rem; color: #94a3b8;
        }
    </style>
</head>
<body>
<div class="maintenance-card">
    <div class="icon-wrap">
        <span class="gear-spin" style="font-size:2.5rem">⚙️</span>
    </div>
    <h1 class="fw-bold text-white mb-2" style="font-size:1.6rem">Sedang Maintenance</h1>
    <p class="text-muted mb-3" style="font-size:.9rem;line-height:1.7">
        {{ $message ?? 'Kami sedang melakukan pemeliharaan sistem. Silakan kembali beberapa saat lagi.' }}
    </p>

    @if(!empty($eta))
    <div class="eta-badge mb-4">
        <i class="fa-regular fa-clock me-1"></i>Estimasi selesai: <strong class="text-white">{{ $eta }}</strong>
    </div>
    @endif

    <hr style="border-color:rgba(255,255,255,.1);margin:1.5rem 0">

    <p class="text-muted mb-0" style="font-size:.78rem">
        <i class="fa-solid fa-cross me-1 text-blue-400"></i>
        I Care True Community — Melayani dengan Kasih
    </p>

    @if(auth()->check() && auth()->user()->isAdmin())
    <div class="mt-3">
        <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-light">
            <i class="fa-solid fa-tools me-1"></i>Panel Admin
        </a>
    </div>
    @endif
</div>
</body>
</html>
