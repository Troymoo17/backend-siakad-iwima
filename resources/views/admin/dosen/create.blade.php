@extends('admin.layouts.app')
@section('title','Tambah Dosen') @section('page-title','Tambah Dosen')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header"><i class="fas fa-user-plus me-2"></i>Form Tambah Dosen</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.dosen.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label fw-semibold">NIDN <span class="text-danger">*</span></label>
                    <input type="text" name="nidn" class="form-control @error('nidn') is-invalid @enderror" value="{{ old('nidn') }}" required>
                    @error('nidn')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label fw-semibold">NPPY</label>
                    <input type="text" name="nppy" class="form-control" value="{{ old('nppy') }}"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6"></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Gelar Depan</label>
                    <input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan') }}" placeholder="Dr., Prof."></div>
                <div class="col-md-5"><label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label fw-semibold">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang') }}" placeholder="M.Kom., Ph.D."></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Prodi</label>
                    <input type="text" name="prodi" class="form-control" value="{{ old('prodi','Informatika') }}"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Jabatan Akademik</label>
                    <select name="jabatan_akademik" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(['Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'] as $j)
                        <option value="{{ $j }}" {{ old('jabatan_akademik')===$j?'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Status Kepegawaian <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['Tetap','Tidak Tetap','Luar Biasa'] as $s)
                        <option value="{{ $s }}" {{ old('status')===$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telp" class="form-control" value="{{ old('telp') }}"></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.dosen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
