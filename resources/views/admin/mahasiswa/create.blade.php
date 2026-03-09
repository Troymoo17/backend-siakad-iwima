@extends('admin.layouts.app')
@section('title', 'Tambah Mahasiswa')
@section('page-title', 'Tambah Mahasiswa')

@section('content')
<div class="card">
    <div class="card-header"><i class="fas fa-user-plus me-2"></i>Form Tambah Mahasiswa</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.mahasiswa.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                    <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                    @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Program <span class="text-danger">*</span></label>
                    <select name="program" class="form-select" required>
                        @foreach(['D3','D4','S1','S2','S3'] as $p)
                        <option value="{{ $p }}" {{ old('program','S1') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Prodi <span class="text-danger">*</span></label>
                    <input type="text" name="prodi" class="form-control" value="{{ old('prodi','Informatika') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" placeholder="IF-2024-A" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Angkatan <span class="text-danger">*</span></label>
                    <input type="number" name="angkatan" class="form-control" value="{{ old('angkatan', date('Y')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dosen PA</label>
                    <select name="dosen_pa_id" class="form-select">
                        <option value="">-- Pilih Dosen PA --</option>
                        @foreach($dosenList as $d)
                        <option value="{{ $d->id }}" {{ old('dosen_pa_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama }} ({{ $d->nidn }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
