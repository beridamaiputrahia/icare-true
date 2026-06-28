<x-guest-layout>
    <h5 class="auth-title">Reset Kata Sandi</h5>
    <p class="auth-sub">Buat kata sandi baru untuk akun Anda.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                <input type="email" id="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                       value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi Baru</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                <input type="password" id="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                       placeholder="Min. 8 karakter" required autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock-open text-muted"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control border-start-0 ps-0"
                       placeholder="Ulangi kata sandi baru" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="fa-solid fa-key me-2"></i>Reset Kata Sandi
        </button>
    </form>
</x-guest-layout>
