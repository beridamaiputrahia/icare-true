@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@push('styles')
<style>
.setting-group { border-left:3px solid var(--app-primary,#2563eb);padding-left:1rem;margin-bottom:.5rem; }
.nav-pills .nav-link { color:#64748b; font-size:.84rem; }
.nav-pills .nav-link.active { background:var(--app-primary,#2563eb); color:#fff; }
.color-preview { width:36px;height:36px;border-radius:6px;border:2px solid #e2e8f0;cursor:pointer;flex-shrink:0; }
.img-preview { max-width:140px;max-height:80px;border-radius:8px;border:1px solid #e2e8f0;object-fit:contain; }
</style>
@endpush

@section('content')
<div class="row g-3">
    {{-- Sidebar Navigation --}}
    <div class="col-12 col-md-3">
        <div class="card sticky-top" style="top:80px">
            <div class="card-body p-2">
                <nav class="nav flex-column nav-pills gap-1">
                    @foreach($groups as $group => $settings)
                    <a class="nav-link" href="#group-{{ $group }}">
                        <i class="fa-solid {{ match($group) {
                            'general'     => 'fa-sliders',
                            'appearance'  => 'fa-palette',
                            'contact'     => 'fa-address-card',
                            'maintenance' => 'fa-tools',
                            'features'    => 'fa-toggle-on',
                            default       => 'fa-cog'
                        } }} me-2"></i>
                        {{ match($group) {
                            'general'     => 'Umum',
                            'appearance'  => 'Tampilan',
                            'contact'     => 'Kontak',
                            'maintenance' => 'Maintenance',
                            'features'    => 'Izin Fitur',
                            default       => ucfirst($group)
                        } }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </div>
    </div>

    {{-- Settings Form --}}
    <div class="col-12 col-md-9">
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            @foreach($groups as $group => $settings)
            <div class="card mb-3" id="group-{{ $group }}">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fa-solid {{ match($group) {
                        'general'     => 'fa-sliders text-primary',
                        'appearance'  => 'fa-palette text-warning',
                        'contact'     => 'fa-address-card text-info',
                        'maintenance' => 'fa-tools text-danger',
                        default       => 'fa-cog text-secondary'
                    } }}"></i>
                    <h6 class="mb-0 fw-semibold">
                        {{ match($group) {
                            'general'     => 'Umum',
                            'appearance'  => 'Tampilan',
                            'contact'     => 'Kontak & Lokasi',
                            'maintenance' => 'Mode Maintenance',
                            'features'    => 'Izin Fitur untuk ICL & CTL',
                            default       => ucfirst($group)
                        } }}
                    </h6>
                    @if($group === 'features')
                    <span class="ms-auto badge bg-warning text-dark" style="font-size:.72rem">
                        <i class="fa-solid fa-shield-halved me-1"></i>Hanya Admin
                    </span>
                    @endif
                    @if($group === 'maintenance')
                    <div class="ms-auto">
                        <form method="POST" action="{{ route('settings.maintenance-toggle') }}" class="d-inline">
                            @csrf
                            @php $isOn = app(\App\Managers\SettingsManager::class)->isMaintenanceMode(); @endphp
                            <button type="submit" class="btn btn-sm {{ $isOn ? 'btn-success' : 'btn-danger' }}">
                                <i class="fa-solid {{ $isOn ? 'fa-toggle-on' : 'fa-toggle-off' }} me-1"></i>
                                {{ $isOn ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($group === 'features')
                    <div class="alert alert-info py-2 px-3 mb-3" style="font-size:.83rem">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Aktifkan fitur di bawah agar <strong>ICL & CTL</strong> dapat melakukan operasi CRUD pada modul tersebut.
                        Admin selalu memiliki akses penuh tanpa tergantung toggle ini.
                    </div>
                    @endif
                    @foreach($settings as $setting)
                    <div class="mb-4 setting-group">
                        <label class="form-label fw-medium" style="font-size:.855rem">
                            {{ $setting->label }}
                        </label>

                        @if($setting->type === 'boolean')
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="{{ $setting->key }}"
                                   id="{{ $setting->key }}" value="1"
                                   {{ $setting->value ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $setting->key }}" style="font-size:.84rem">
                                @if($group === 'features')
                                <span class="text-success fw-medium">Aktif</span> — ICL & CTL dapat mengakses
                                @else
                                Aktif
                                @endif
                            </label>
                        </div>

                        @elseif($setting->type === 'color')
                        <div class="d-flex align-items-center gap-2">
                            <div class="color-preview" id="preview-{{ $setting->key }}"
                                 style="background:{{ $setting->value }}"
                                 onclick="document.getElementById('{{ $setting->key }}').click()"></div>
                            <input type="color" name="{{ $setting->key }}" id="{{ $setting->key }}"
                                   value="{{ $setting->value }}" class="form-control form-control-color"
                                   style="width:48px"
                                   onchange="document.getElementById('preview-{{ $setting->key }}').style.background=this.value">
                            <input type="text" class="form-control form-control-sm" style="max-width:110px"
                                   value="{{ $setting->value }}" readonly
                                   id="hex-{{ $setting->key }}">
                        </div>

                        @elseif($setting->type === 'image')
                        <div>
                            @if($setting->value)
                            <img src="{{ Storage::url($setting->value) }}" class="img-preview d-block mb-2">
                            @endif
                            <input type="file" name="{{ $setting->key }}" class="form-control form-control-sm"
                                   accept="image/*" style="max-width:320px">
                            @if($setting->value)
                            <p class="text-muted mt-1 mb-0" style="font-size:.75rem">
                                <i class="fa-solid fa-circle-info me-1"></i>Upload baru untuk mengganti gambar saat ini
                            </p>
                            @endif
                        </div>

                        @elseif($setting->type === 'textarea')
                        <textarea name="{{ $setting->key }}" rows="3"
                                  class="form-control @error($setting->key) is-invalid @enderror"
                                  style="font-size:.875rem">{{ old($setting->key, $setting->value) }}</textarea>

                        @else
                        <input type="text" name="{{ $setting->key }}"
                               class="form-control @error($setting->key) is-invalid @enderror"
                               value="{{ old($setting->key, $setting->value) }}"
                               style="font-size:.875rem">
                        @endif

                        @error($setting->key)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Semua Pengaturan
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sync color picker to hex input
document.querySelectorAll('input[type="color"]').forEach(function(picker) {
    const key = picker.name;
    const hex = document.getElementById('hex-' + key);
    picker.addEventListener('input', function() {
        if (hex) hex.value = this.value;
    });
});

// Smooth scroll for nav pills
document.querySelectorAll('.nav-pills .nav-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
@endpush
