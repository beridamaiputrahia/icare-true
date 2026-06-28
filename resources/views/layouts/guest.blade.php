<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'I Care True') }} — {{ $title ?? 'Masuk' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #1e3a5f 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem 1rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 520px;          /* wider for registration form */
            box-shadow: 0 25px 50px rgba(0,0,0,.3);
        }
        .auth-logo {
            width: 56px; height: 56px;
            background: #2563eb; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .auth-title { font-size: 1.375rem; font-weight: 700; color: #1e293b; text-align: center; }
        .auth-sub   { font-size: .825rem; color: #64748b; text-align: center; margin-bottom: 1.75rem; }
        .form-label { font-size: .825rem; font-weight: 500; color: #374151; }
        .form-control {
            border-radius: 8px; font-size: .875rem;
            border-color: #d1d5db; padding: .6rem .875rem;
        }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .input-group-text {
            border-radius: 8px 0 0 8px !important;
            border-color: #d1d5db;
        }
        .input-group .form-control { border-radius: 0 8px 8px 0 !important; }
        .btn-primary {
            background: #2563eb; border-color: #2563eb;
            border-radius: 8px; font-weight: 600; padding: .65rem 1.25rem;
        }
        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; }
        .is-invalid { border-color: #dc3545 !important; }
        .invalid-feedback { font-size: .775rem; }
        .form-check-input:checked { background-color: #2563eb; border-color: #2563eb; }
        .section-divider {
            font-size: .7rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .08em; color: #94a3b8;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: .35rem; margin-bottom: 1rem; margin-top: .25rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-card">
        <div class="auth-logo">
            <i class="fa-solid fa-cross text-white fa-lg"></i>
        </div>
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
