@extends('layouts.app')

@section('title', $schedule->nama_kegiatan)
@section('page-title', 'Detail Jadwal')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}" class="text-decoration-none text-muted">Jadwal</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-days text-primary me-2"></i>Detail Jadwal Kegiatan</h6>
                <span class="badge badge-{{ $schedule->status }} text-capitalize fs-6">{{ $schedule->status }}</span>
            </div>
            <div class="card-body">
                <h4 class="fw-bold mb-1">{{ $schedule->nama_kegiatan }}</h4>
                <p class="text-muted small mb-4">Dibuat oleh {{ $schedule->creator->name ?? 'Admin' }} · {{ $schedule->created_at->diffForHumans() }}</p>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 bg-light">
                            <i class="fa-solid fa-calendar text-primary fa-lg"></i>
                            <div>
                                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600">Tanggal</div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $schedule->formatted_date }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 bg-light">
                            <i class="fa-regular fa-clock text-success fa-lg"></i>
                            <div>
                                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600">Jam</div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $schedule->formatted_time }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 bg-light">
                            <i class="fa-solid fa-location-dot text-danger fa-lg"></i>
                            <div>
                                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600">Lokasi</div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $schedule->lokasi }}</div>
                                @if($schedule->link_maps)
                                <a href="{{ $schedule->link_maps }}" target="_blank" class="text-primary" style="font-size:.75rem">
                                    <i class="fa-solid fa-map me-1"></i>Buka di Google Maps
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($schedule->pembicara)
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2 p-3 rounded-3 bg-light">
                            <i class="fa-solid fa-person-chalkboard text-warning fa-lg"></i>
                            <div>
                                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;font-weight:600">Pembicara</div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $schedule->pembicara }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($schedule->deskripsi)
                <div class="mb-4">
                    <h6 class="fw-semibold text-muted mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">Deskripsi</h6>
                    <p class="mb-0" style="line-height:1.8;font-size:.9rem">{{ $schedule->deskripsi }}</p>
                </div>
                @endif

                {{-- Google Maps embed --}}
                @if($schedule->link_maps)
                <div class="mb-4">
                    <h6 class="fw-semibold text-muted mb-2" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.05em">
                        <i class="fa-solid fa-map-location-dot text-danger me-1"></i>Lokasi di Google Maps
                    </h6>
                    <div class="rounded-3 overflow-hidden border" style="height:240px">
                        <iframe
                            src="{{ $schedule->maps_embed_url }}"
                            width="100%" height="240" style="border:0;display:block"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="mt-2">
                        <a href="{{ $schedule->link_maps }}" target="_blank" rel="noopener"
                           class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-diamond-turn-right me-1"></i>Navigasi Google Maps
                        </a>
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>Kembali
                    </a>
                    {{-- Export PDF --}}
                    <a href="{{ route('export.schedule.pdf', $schedule) }}" class="btn btn-outline-danger btn-sm" target="_blank">
                        <i class="fa-solid fa-file-pdf me-1"></i>Export PDF
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-pen me-1"></i>Edit
                    </a>
                    <form id="del-{{ $schedule->id }}" method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="d-inline">
                        @csrf @method('DELETE')
                    </form>
                    <button class="btn btn-danger btn-sm btn-delete" data-form="del-{{ $schedule->id }}">
                        <i class="fa-solid fa-trash me-1"></i>Hapus
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
