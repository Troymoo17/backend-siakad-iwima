@extends('admin.layouts.app')
@section('title','Edit Jadwal') @section('page-title','Edit Jadwal Kuliah')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Edit Jadwal: {{ $jadwal->nama_mk }} - {{ $jadwal->kelas }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.jadwal.update',$jadwal->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Mata Kuliah</label>
                    <select name="kode_mk" class="form-select select2" required>
                        @foreach($mkList as $mk)
                        <option value="{{ $mk->kode_mk }}" {{ old('kode_mk',$jadwal->kode_mk)===$mk->kode_mk?'selected':'' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Dosen Pengampu</label>
                    <select name="dosen_id" class="form-select select2">
                        <option value="">-- Tidak ada --</option>
                        @foreach($dosenList as $d)
                        <option value="{{ $d->id }}" {{ old('dosen_id',$jadwal->dosen_id)==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Kelas</label>
                    <input type="text" name="kelas" class="form-control" value="{{ old('kelas',$jadwal->kelas) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Ruang</label>
                    <input type="text" name="ruang" class="form-control" value="{{ old('ruang',$jadwal->ruang) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Hari</label>
                    <select name="hari" class="form-select" required>
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                        <option value="{{ $h }}" {{ old('hari',$jadwal->hari)===$h?'selected':'' }}>{{ $h }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', substr($jadwal->jam_mulai,0,5)) }}" required></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', substr($jadwal->jam_selesai,0,5)) }}" required></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="Teori" {{ old('jenis',$jadwal->jenis)==='Teori'?'selected':'' }}>Teori</option>
                        <option value="Praktikum" {{ old('jenis',$jadwal->jenis)==='Praktikum'?'selected':'' }}>Praktikum</option>
                    </select></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Semester</label>
                    <input type="number" name="semester" class="form-control" value="{{ old('semester',$jadwal->semester) }}" min="1" max="14" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik',$jadwal->tahun_akademik) }}" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Google Classroom ID</label>
                    <input type="text" name="google_classroom_id" class="form-control" value="{{ old('google_classroom_id',$jadwal->google_classroom_id) }}"></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
