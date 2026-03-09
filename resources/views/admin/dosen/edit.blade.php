@extends('admin.layouts.app')
@section('title','Edit Dosen') @section('page-title','Edit Dosen')
@section('content')
<div class="card" style="max-width:720px">
    <div class="card-header"><i class="fas fa-user-edit me-2"></i>Edit: {{ $dosen->nama }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.dosen.update',$dosen->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label fw-semibold">NIDN</label>
                    <input type="text" class="form-control bg-light" value="{{ $dosen->nidn }}" disabled></div>
                <div class="col-md-4"><label class="form-label fw-semibold">NPPY</label>
                    <input type="text" name="nppy" class="form-control" value="{{ old('nppy',$dosen->nppy) }}"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control" minlength="6"></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Gelar Depan</label>
                    <input type="text" name="gelar_depan" class="form-control" value="{{ old('gelar_depan',$dosen->gelar_depan) }}"></div>
                <div class="col-md-5"><label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama',$dosen->nama) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Gelar Belakang</label>
                    <input type="text" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang',$dosen->gelar_belakang) }}"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Prodi</label>
                    <input type="text" name="prodi" class="form-control" value="{{ old('prodi',$dosen->prodi) }}"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Jabatan Akademik</label>
                    <select name="jabatan_akademik" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(['Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'] as $j)
                        <option value="{{ $j }}" {{ old('jabatan_akademik',$dosen->jabatan_akademik)===$j?'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['Tetap','Tidak Tetap','Luar Biasa'] as $s)
                        <option value="{{ $s }}" {{ old('status',$dosen->status)===$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email',$dosen->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-md-4"><label class="form-label fw-semibold">Telepon</label>
                    <input type="text" name="telp" class="form-control" value="{{ old('telp',$dosen->telp) }}"></div>
                <div class="col-md-4 d-flex align-items-end pb-1">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ $dosen->is_active?'checked':'' }}>
                        <label class="form-check-label fw-semibold" for="is_active">Aktif</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.dosen.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
