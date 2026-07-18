@extends('layouts.app')

@section('title', $member->nama_lengkap)
@section('page-title', 'Profil Anggota')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('members.index') }}" class="text-decoration-none text-muted">Anggota</a></li>
    <li class="breadcrumb-item active">Profil</li>
@endsection

@push('styles')
<style>
.profile-hero {
    border-radius: 16px; padding: 2rem 2rem 1.5rem;
    position: relative; overflow: hidden;
}
.profile-hero::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(0,0,0,.35) 0%, rgba(0,0,0,.1) 100%);
}
.profile-hero > * { position: relative; z-index: 1; }
.avatar-ring {
    width: 90px; height: 90px; border-radius: 50%;
    border: 3px solid rgba(255,255,255,.7);
    object-fit: cover;
}
.avatar-placeholder {
    width: 90px; height: 90px; border-radius: 50%;
    border: 3px solid rgba(255,255,255,.7);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; font-weight: 700; color: #fff;
    background: rgba(255,255,255,.2);
    flex-shrink: 0;
}
.badge-mini { border-radius: 8px; padding: .45rem .6rem; text-align: center; flex: 1; min-width: 72px; }
.badge-mini.achieved { border: 1.5px solid #22c55e; }
.badge-mini.locked { opacity: .4; filter: grayscale(1); }
.progress { height: 6px; border-radius: 6px; }
</style>
@endpush

@section('content')
<div class="row g-3">
    {{-- Left Column --}}
    <div class="col-12 col-lg-4">

        {{-- Banner Hero Card --}}
        @php $banner = $user?->banner; @endphp
        <div class="profile-hero mb-3"
             style="{{ $banner ? $banner->gradient_style : 'background:linear-gradient(135deg,#2563eb,#1d4ed8)' }}">
            <div class="d-flex align-items-center gap-3 mb-3">
                @if($member->foto)
                <img src="{{ Storage::url($member->foto) }}" class="avatar-ring">
                @else
                <div class="avatar-placeholder">{{ strtoupper(substr($member->nama_lengkap, 0, 1)) }}</div>
                @endif
                <div>
                    <h5 class="fw-bold mb-0 text-white">{{ $member->nama_lengkap }}</h5>
                    @if($member->nama_panggilan)
                    <p class="mb-1 text-white" style="opacity:.8;font-size:.85rem">"{{ $member->nama_panggilan }}"</p>
                    @endif
                    @if($banner)
                    <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.7rem">
                        <i class="fa-solid {{ $banner->icon }} me-1"></i>{{ $banner->label }}
                    </span>
                    @else
                    <span class="badge" style="background:rgba(255,255,255,.2);color:#fff;font-size:.7rem">
                        <i class="fa-solid fa-user me-1"></i>Anggota
                    </span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-3 text-white" style="font-size:.78rem;opacity:.85">
                @if($member->covenant_number)
                <span><i class="fa-solid fa-id-card me-1"></i>{{ $member->covenant_number }}</span>
                @endif
                <span>
                    <i class="fa-solid fa-circle me-1" style="font-size:.4rem;vertical-align:middle"></i>
                    {{ $member->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 fw-semibold" style="font-size:.82rem"><i class="fa-solid fa-circle-info text-primary me-2"></i>Informasi</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.84rem">
                    <tr>
                        <td class="text-muted fw-medium" style="width:35%">Nomor HP</td>
                        <td>
                            @if($member->nomor_hp)
                            <a href="tel:{{ $member->nomor_hp }}" class="text-decoration-none">{{ $member->nomor_hp }}</a>
                            @else <span class="text-muted">-</span> @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium">Tgl Lahir</td>
                        <td>
                            @if($member->tanggal_lahir)
                            {{ $member->tanggal_lahir->translatedFormat('d F Y') }}
                            <span class="text-muted">({{ $member->age }} thn)</span>
                            @else <span class="text-muted">-</span> @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium">Bergabung</td>
                        <td>{{ $member->created_at->translatedFormat('d F Y') }}</td>
                    </tr>
                    @if($member->alamat)
                    <tr>
                        <td class="text-muted fw-medium">Alamat</td>
                        <td>{{ $member->alamat }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i>Kembali
            </a>
            <a href="{{ route('qr.member', $member) }}" class="btn btn-sm btn-outline-primary">
                <i class="fa-solid fa-qrcode me-1"></i>Kartu Anggota
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('members.edit', $member) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen me-1"></i>Edit & Kelola Akun
            </a>
            <form id="del-member" method="POST" action="{{ route('members.destroy', $member) }}" class="d-inline">
                @csrf @method('DELETE')
            </form>
            <button class="btn btn-danger btn-sm btn-delete" data-form="del-member">
                <i class="fa-solid fa-trash me-1"></i>Hapus
            </button>
            @endif
        </div>

        {{-- Account status card --}}
        @if(auth()->user()->isAdmin())
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center justify-content-between py-2">
                <h6 class="mb-0 fw-semibold" style="font-size:.8rem">
                    <i class="fa-solid fa-shield-halved text-primary me-2"></i>Akun Login
                </h6>
                @if($user)
                <div class="d-flex gap-1 align-items-center">
                    <span class="badge bg-{{ $user->roleColor() }} text-uppercase" style="font-size:.6rem;letter-spacing:.05em">
                        {{ $user->roleLabel() }}
                    </span>
                    @if($user->secondary_role)
                    <span class="badge bg-secondary text-uppercase" style="font-size:.6rem;letter-spacing:.05em">
                        {{ $user->secondaryRoleLabel() }}
                    </span>
                    @endif
                </div>
                @endif
            </div>
            <div class="card-body py-2" style="font-size:.8rem">
                @if($user)
                {{-- Email --}}
                <div class="mb-2">
                    <div class="text-muted" style="font-size:.72rem">Email</div>
                    <div class="fw-medium">{{ $user->email }}</div>
                </div>

                {{-- Ubah Role Utama --}}
                <form method="POST" action="{{ route('members.role', $member) }}" class="d-flex gap-2 align-items-center mb-2">
                    @csrf @method('PATCH')
                    <div class="flex-grow-1">
                        <label class="text-muted mb-1" style="font-size:.72rem">Role Utama</label>
                        <select name="role" class="form-select form-select-sm">
                            <option value="anggota" {{ $user->role === 'anggota' ? 'selected':'' }}>Anggota</option>
                            <option value="ctl"     {{ $user->role === 'ctl'     ? 'selected':'' }}>CTL (Co-Team Leader)</option>
                            <option value="icl"     {{ $user->role === 'icl'     ? 'selected':'' }}>ICL (I Care Leader)</option>
                            <option value="admin"   {{ $user->role === 'admin'   ? 'selected':'' }}>Admin</option>
                        </select>
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-sm btn-outline-danger text-nowrap">
                            <i class="fa-solid fa-shield me-1"></i>Simpan
                        </button>
                    </div>
                </form>

                {{-- Double Role (hanya tampil jika user adalah admin) --}}
                @if($user->isAdmin())
                <form method="POST" action="{{ route('members.secondary-role', $member) }}" class="d-flex gap-2 align-items-center">
                    @csrf @method('PATCH')
                    <div class="flex-grow-1">
                        <label class="text-muted mb-1" style="font-size:.72rem">
                            Role Tambahan <span class="text-secondary">(opsional — khusus Admin)</span>
                        </label>
                        <select name="secondary_role" class="form-select form-select-sm">
                            <option value="">— Tidak Ada —</option>
                            <option value="icl"     {{ $user->secondary_role === 'icl'     ? 'selected':'' }}>+ ICL (I Care Leader)</option>
                            <option value="ctl"     {{ $user->secondary_role === 'ctl'     ? 'selected':'' }}>+ CTL (Co-Team Leader)</option>
                            <option value="anggota" {{ $user->secondary_role === 'anggota' ? 'selected':'' }}>+ Anggota</option>
                        </select>
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-sm btn-outline-secondary text-nowrap">
                            <i class="fa-solid fa-layer-group me-1"></i>Simpan
                        </button>
                    </div>
                </form>
                @endif

                @else
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-muted"><i class="fa-solid fa-xmark me-1 text-danger"></i>Belum punya akun login</span>
                    <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-success">
                        <i class="fa-solid fa-plus me-1"></i>Buat Akun
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Right Column --}}
    <div class="col-12 col-lg-8">

        @if($user)
        {{-- Stats mini --}}
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="stat-card text-center py-3">
                    <div class="stat-value text-primary">{{ $user->approved_devotions_count }}</div>
                    <div class="stat-label">Sharing Firman</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-card text-center py-3">
                    <div class="stat-value text-warning">{{ $user->total_devotions_count }}</div>
                    <div class="stat-label">Renungan</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-card text-center py-3">
                    <div class="stat-value text-success">{{ $myBadges->count() }}</div>
                    <div class="stat-label">Badge</div>
                </div>
            </div>
        </div>

        {{-- Progress bars --}}
        @if(!empty($progress))
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 fw-semibold" style="font-size:.82rem"><i class="fa-solid fa-chart-simple text-primary me-2"></i>Progress Achievement</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($progress as $category => $data)
                    <div class="col-12 col-sm-6">
                        <p class="fw-semibold mb-2" style="font-size:.78rem;color:#64748b;text-transform:uppercase;letter-spacing:.04em">
                            {{ $category === 'sharing_firman' ? 'Sharing Firman' : 'Renungan' }}
                        </p>
                        @foreach($data['steps'] as $step)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:.74rem;color:{{ $step['achieved']?'#22c55e':'#94a3b8' }}">
                                    @if($step['achieved'])<i class="fa-solid fa-check me-1"></i>@endif
                                    {{ $step['label'] }}
                                </span>
                                <span style="font-size:.7rem;color:#cbd5e1">{{ $data['count'] }}/{{ $step['required'] }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar {{ $step['achieved']?'bg-success':'bg-primary' }}"
                                     style="width:{{ $step['percent'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Badges grid --}}
        @if($allBadges->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0 fw-semibold" style="font-size:.82rem"><i class="fa-solid fa-trophy text-warning me-2"></i>Badge</h6></div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($allBadges as $badge)
                    @php $achieved = $myBadges->contains('id', $badge->id); @endphp
                    <div class="badge-mini stat-card {{ $achieved ? 'achieved' : 'locked' }}" style="min-width:72px">
                        <div style="font-size:1.1rem;margin-bottom:.25rem">
                            <i class="fa-solid {{ $badge->icon }}" style="color:{{ $badge->color }}"></i>
                        </div>
                        <div style="font-size:.68rem;font-weight:600;color:#374151">{{ $badge->badge_label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Level & Points Card ─────────────────────────────────── --}}
        @php $lvlProgress = $user->level_progress; @endphp
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold" style="font-size:.82rem">
                    <i class="fa-solid fa-coins text-warning me-2"></i>Poin & Level Komunitas
                </h6>
                <a href="{{ route('leaderboard.index') }}" class="btn btn-xs btn-outline-primary" style="font-size:.7rem;padding:.15rem .5rem">
                    Leaderboard
                </a>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:{{ $lvlProgress['current_color'] }}20">
                        <i class="fa-solid {{ $lvlProgress['current_icon'] }} fa-lg" style="color:{{ $lvlProgress['current_color'] }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-weight:700;font-size:.9rem;color:{{ $lvlProgress['current_color'] }}">
                            Level {{ $lvlProgress['current_level'] }} — {{ $lvlProgress['current_name'] }}
                        </div>
                        <div style="font-size:.75rem;color:#64748b">
                            {{ number_format($user->total_points) }} poin total
                        </div>
                    </div>
                </div>
                @if(!$lvlProgress['is_max'])
                <div class="d-flex justify-content-between mb-1" style="font-size:.72rem;color:#94a3b8">
                    <span>Progress ke {{ $lvlProgress['next_name'] }}</span>
                    <span>{{ $lvlProgress['points_to_next'] }} poin lagi</span>
                </div>
                <div class="progress" style="height:8px">
                    <div class="progress-bar" style="width:{{ $lvlProgress['percent'] }}%;background:{{ $lvlProgress['current_color'] }}"></div>
                </div>
                @endif
            </div>
        </div>

        {{-- Activity Log --}}
        @if($activityLogs->isNotEmpty())
        <div class="card">
            <div class="card-header"><h6 class="mb-0 fw-semibold" style="font-size:.82rem"><i class="fa-solid fa-timeline text-primary me-2"></i>Riwayat Aktivitas</h6></div>
            <div class="card-body p-0">
                @foreach($activityLogs as $log)
                <div class="d-flex gap-3 px-4 py-3 {{ !$loop->last ? 'border-bottom':'' }}">
                    @php
                        $logIcon = match(true) {
                            str_contains($log->type,'achievement') => 'fa-trophy text-warning',
                            str_contains($log->type,'approved')    => 'fa-check-circle text-success',
                            str_contains($log->type,'created')     => 'fa-pen text-primary',
                            default                                 => 'fa-circle-dot text-secondary',
                        };
                    @endphp
                    <div class="flex-shrink-0 mt-1"><i class="fa-solid {{ $logIcon }}" style="font-size:.85rem"></i></div>
                    <div class="flex-grow-1">
                        <p class="mb-0" style="font-size:.83rem">{{ $log->description }}</p>
                        <p class="mb-0 text-muted" style="font-size:.72rem">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @else
        {{-- No user account linked --}}
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="fa-solid fa-user-slash fa-2x mb-3 opacity-25 d-block"></i>
                <p class="mb-0" style="font-size:.85rem">Anggota ini belum memiliki akun yang terhubung.</p>
                <p class="mb-0 mt-1" style="font-size:.78rem">Achievement dan riwayat aktivitas tidak tersedia.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
