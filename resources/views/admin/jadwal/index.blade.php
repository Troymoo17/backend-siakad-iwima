@extends('admin.layouts.app')
@section('title','Jadwal Kuliah') @section('page-title','Jadwal Kuliah')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-success"></i>Tambah Jadwal Kuliah</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.jadwal.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_mk" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            @foreach($mkList as $mk)
                            <option value="{{ $mk->kode_mk }}" {{ old('kode_mk')===$mk->kode_mk?'selected':'' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                            @endforeach
                        </select></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Dosen Pengampu</label>
                        <select name="dosen_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}" {{ old('dosen_id')==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" placeholder="IF-2022-A" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Ruang <span class="text-danger">*</span></label>
                            <input type="text" name="ruang" class="form-control" value="{{ old('ruang') }}" placeholder="R.101" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                        <select name="hari" class="form-select" required>
                            <option value="">-- Pilih Hari --</option>
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}" {{ old('hari')===$h?'selected':'' }}>{{ $h }}</option>
                            @endforeach
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-5"><label class="form-label fw-semibold">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="Teori" {{ old('jenis')==='Teori'?'selected':'' }}>Teori</option>
                                <option value="Praktikum" {{ old('jenis')==='Praktikum'?'selected':'' }}>Praktikum</option>
                            </select></div>
                        <div class="col-3"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ old('semester',1) }}" min="1" max="14" required></div>
                        <div class="col-4"><label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik','2025/2026') }}" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Google Classroom ID <small class="text-muted">(opsional)</small></label>
                        <input type="text" name="google_classroom_id" class="form-control" value="{{ old('google_classroom_id') }}" placeholder="classroom.google.com/..."></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan Jadwal</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-calendar-alt me-2"></i>Daftar Jadwal Kuliah ({{ $jadwals->total() }})</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-4"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari MK / Kelas..." value="{{ request('search') }}"></div>
                    <div class="col-md-2">
                        <select name="hari" class="form-select form-select-sm">
                            <option value="">Semua Hari</option>
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                            <option value="{{ $h }}" {{ request('hari')===$h?'selected':'' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><input type="text" name="kelas" class="form-control form-control-sm" placeholder="Kelas" value="{{ request('kelas') }}"></div>
                    <div class="col-md-2"><input type="text" name="tahun_akademik" class="form-control form-control-sm" placeholder="2025/2026" value="{{ request('tahun_akademik') }}"></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>Hari</th><th>MK / Kelas</th><th>Waktu</th><th>Ruang</th><th>Dosen</th><th>Jenis</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($jadwals as $j)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ $j->hari }}</span>
                            </td>
                            <td>
                                <strong>{{ $j->kode_mk }}</strong><br>
                                <small class="text-muted">{{ $j->nama_mk }}</small><br>
                                <span class="badge bg-secondary">{{ $j->kelas }}</span>
                            </td>
                            <td><small>{{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</small></td>
                            <td>{{ $j->ruang }}</td>
                            <td><small>{{ $j->dosen?->nama ?? '-' }}</small></td>
                            <td><span class="badge {{ $j->jenis==='Praktikum'?'bg-warning text-dark':'bg-info' }}">{{ $j->jenis }}</span></td>
                            <td>
                                <a href="{{ route('admin.jadwal.edit',$j->id) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.jadwal.destroy',$j->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada jadwal kuliah</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $jadwals->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
