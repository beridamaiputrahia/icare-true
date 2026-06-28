<x-guest-layout>
    <h5 class="auth-title">Masuk ke I Care True</h5>
    <p class="auth-sub">Pusat Informasi Komunitas Rohani Kristen</p>

    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="font-size:.82rem">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                <input type="email" id="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Kata Sandi</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size:.8rem;color:#2563eb">Lupa password?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                <input type="password" id="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                       placeholder="••••••••" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me" style="font-size:.82rem">Ingat saya</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
        </button>

        @if(Route::has('register'))
        <p class="text-center mb-0" style="font-size:.82rem;color:#64748b">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#2563eb">Daftar sekarang</a>
        </p>
        @endif
    </form>
</x-guest-layout>
