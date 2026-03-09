@extends('admin.layouts.app')
@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Mahasiswa')

@section('content')
<div class="card">
    <div class="card-header"><i class="fas fa-user-edit me-2"></i>Edit Mahasiswa: {{ $mahasiswa->nama }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa->nim) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">NIM</label>
                    <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" disabled>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama', $mahasiswa->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $mahasiswa->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="kelas" class="form-control" value="{{ old('kelas', $mahasiswa->kelas) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Semester Sekarang <span class="text-danger">*</span></label>
                    <input type="number" name="semester_sekarang" class="form-control" min="1" max="14"
                           value="{{ old('semester_sekarang', $mahasiswa->semester_sekarang) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status Aktif <span class="text-danger">*</span></label>
                    <select name="status_aktif" class="form-select" required>
                        @foreach(['Aktif','Cuti','Keluar','Lulus'] as $s)
                        <option value="{{ $s }}" {{ old('status_aktif', $mahasiswa->status_aktif) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dosen PA</label>
                    <select name="dosen_pa_id" class="form-select">
                        <option value="">-- Pilih Dosen PA --</option>
                        @foreach($dosenList as $d)
                        <option value="{{ $d->id }}" {{ old('dosen_pa_id', $mahasiswa->dosen_pa_id) == $d->id ? 'selected' : '' }}>
                            {{ $d->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
