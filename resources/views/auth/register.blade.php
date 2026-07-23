<x-guest-layout>
    <h5 class="auth-title">Daftar Akun Baru</h5>
    <p class="auth-sub">Bergabung dengan komunitas I Care IFGF Ungaran</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama Lengkap --}}
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                       class="form-control border-start-0 ps-0 @error('nama_lengkap') is-invalid @enderror"
                       value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap Anda" required autofocus>
                @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Nama Panggilan --}}
        <div class="mb-3">
            <label for="nama_panggilan" class="form-label">Nama Panggilan</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-id-badge text-muted"></i></span>
                <input type="text" id="nama_panggilan" name="nama_panggilan"
                       class="form-control border-start-0 ps-0 @error('nama_panggilan') is-invalid @enderror"
                       value="{{ old('nama_panggilan') }}" placeholder="Nama panggilan (opsional)">
                @error('nama_panggilan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Tanggal Lahir --}}
        <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar text-muted"></i></span>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                       class="form-control border-start-0 ps-0 @error('tanggal_lahir') is-invalid @enderror"
                       value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}">
                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot text-muted"></i></span>
                <input type="text" id="alamat" name="alamat"
                       class="form-control border-start-0 ps-0 @error('alamat') is-invalid @enderror"
                       value="{{ old('alamat') }}" placeholder="Alamat tempat tinggal">
                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Nomor HP --}}
        <div class="mb-3">
            <label for="nomor_hp" class="form-label">Nomor HP</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                <input type="tel" id="nomor_hp" name="nomor_hp"
                       class="form-control border-start-0 ps-0 @error('nomor_hp') is-invalid @enderror"
                       value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx">
                @error('nomor_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Covenant Toggle --}}
        <div class="mb-3">
            <label class="form-label d-flex align-items-center justify-content-between">
                <span>Nomor Covenant</span>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="covenant_aktif" name="covenant_aktif"
                           value="1" {{ old('covenant_aktif') ? 'checked' : '' }}
                           onchange="toggleCovenant(this.checked)">
                    <label class="form-check-label" for="covenant_aktif" style="font-size:.8rem;color:#64748b">
                        Saya punya nomor covenant
                    </label>
                </div>
            </label>
            <div id="covenant_field" style="{{ old('covenant_aktif') ? '' : 'display:none' }}">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-hashtag text-muted"></i></span>
                    <input type="text" id="covenant_number" name="covenant_number"
                           class="form-control border-start-0 ps-0 @error('covenant_number') is-invalid @enderror"
                           value="{{ old('covenant_number') }}" placeholder="Nomor covenant Anda">
                    @error('covenant_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- I Care Group --}}
        <div class="mb-3">
            <label for="tenant_id" class="form-label">I Care Group <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-people-group text-muted"></i></span>
                <select id="tenant_id" name="tenant_id"
                        class="form-select border-start-0 ps-0 @error('tenant_id') is-invalid @enderror" required>
                    <option value="">— Pilih I Care Group —</option>
                    @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ (string) old('tenant_id') === (string) $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->nama_perusahaan }}
                    </option>
                    @endforeach
                </select>
                @error('tenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            @if($tenants->isEmpty())
            <small class="text-danger">Belum ada I Care Group yang tersedia. Hubungi admin.</small>
            @endif
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                <input type="email" id="email" name="email"
                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="username">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                <input type="password" id="password" name="password"
                       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                       placeholder="Min. 8 karakter" required autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-lock-open text-muted"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control border-start-0 ps-0"
                       placeholder="Ulangi kata sandi" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fa-solid fa-user-plus me-2"></i>Buat Akun
        </button>

        <p class="text-center mb-0" style="font-size:.82rem;color:#64748b">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#2563eb">Masuk di sini</a>
        </p>
    </form>

    @push('scripts')
    <script>
    function toggleCovenant(active) {
        const field = document.getElementById('covenant_field');
        const input = document.getElementById('covenant_number');
        field.style.display = active ? '' : 'none';
        if (!active) input.value = '';
    }
    </script>
    @endpush
</x-guest-layout>
