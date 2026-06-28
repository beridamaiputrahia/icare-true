@extends('layouts.app')

@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal Kegiatan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}" class="text-decoration-none text-muted">Jadwal</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold"><i class="fa-solid fa-calendar-pen text-warning me-2"></i>Edit Jadwal: {{ $schedule->nama_kegiatan }}</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('schedules.update', $schedule) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                   value="{{ old('nama_kegiatan', $schedule->nama_kegiatan) }}" required>
                            @error('nama_kegiatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', $schedule->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jam <span class="text-danger">*</span></label>
                            <input type="time" name="jam" class="form-control @error('jam') is-invalid @enderror"
                                   value="{{ old('jam', $schedule->jam) }}" required>
                            @error('jam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                   value="{{ old('lokasi', $schedule->lokasi) }}" required>
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pembicara</label>
                            <input type="text" name="pembicara" class="form-control @error('pembicara') is-invalid @enderror"
                                   value="{{ old('pembicara', $schedule->pembicara) }}">
                            @error('pembicara')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Link Google Maps</label>
                            <input type="url" name="link_maps" class="form-control @error('link_maps') is-invalid @enderror"
                                   value="{{ old('link_maps', $schedule->link_maps) }}">
                            @error('link_maps')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="upcoming" {{ old('status',$schedule->status)=='upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing"  {{ old('status',$schedule->status)=='ongoing'  ? 'selected' : '' }}>Ongoing</option>
                                <option value="done"     {{ old('status',$schedule->status)=='done'     ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $schedule->deskripsi) }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Perbarui Jadwal
                        </button>
                        <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
