<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>I Care True</title>
    <style>
        * { margin:0;padding:0;box-sizing:border-box; }
        body {
            font-family:'Segoe UI',sans-serif;
            min-height:100vh;
            background:#0f172a;
            display:flex;align-items:center;justify-content:center;
        }
        .spinner {
            width:36px;height:36px;border-radius:50%;
            border:3px solid rgba(255,255,255,.15);
            border-top-color:#2563eb;
            animation:spin .7s linear infinite;
        }
        @keyframes spin { to { transform:rotate(360deg); } }
    </style>
</head>
<body>
    <div class="spinner"></div>
    <script>
        {{-- start_url PWA selalu membuka '/' — daripada server memaksa
             redirect ke /dashboard (membuang halaman terakhir user), baca
             halaman terakhir dari localStorage (diisi layouts/app.blade.php
             di tiap page load) dan lanjutkan ke sana. --}}
        var last = null;
        try { last = localStorage.getItem('icare_last_path'); } catch (e) {}
        var valid = last && last.startsWith('/') && !last.startsWith('//') && last !== '/';
        window.location.replace(valid ? last : '{{ route('dashboard') }}');
    </script>
</body>
</html>
