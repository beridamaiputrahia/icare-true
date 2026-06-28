<x-guest-layout>
    <h5 class="auth-title">Lupa Kata Sandi?</h5>
    <p class="auth-sub">Masukkan email Anda dan kami akan mengirimkan link reset password.</p>

    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="font-size:.82rem">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                <input type="email" id="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="Masukkan email terdaftar" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fa-solid fa-paper-plane me-2"></i>Kirim Link Reset
        </button>

        <p class="text-center mb-0" style="font-size:.82rem;color:#64748b">
            <a href="{{ route('login') }}" class="text-decoration-none" style="color:#2563eb">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Login
            </a>
        </p>
    </form>
</x-guest-layout>
