@extends('admin.layouts.app')
@section('title','Jadwal Ujian') @section('page-title','Jadwal Ujian')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-success"></i>Tambah Jadwal Ujian</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.ujian.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_mk" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            @foreach($mkList as $mk)
                            <option value="{{ $mk->kode_mk }}" {{ old('kode_mk')===$mk->kode_mk?'selected':'' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                            @endforeach
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-7"><label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="kelas" class="form-control" value="{{ old('kelas') }}" placeholder="IF-2022-A" required></div>
                        <div class="col-5"><label class="form-label fw-semibold">Jenis Ujian</label>
                            <select name="jenis_ujian" class="form-select" required>
                                <option value="UTS" {{ old('jenis_ujian')==='UTS'?'selected':'' }}>UTS</option>
                                <option value="UAS" {{ old('jenis_ujian')==='UAS'?'selected':'' }}>UAS</option>
                            </select></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Dosen Pengawas</label>
                        <select name="dosen_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}" {{ old('dosen_id')==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-7"><label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required id="tglUjian"></div>
                        <div class="col-5"><label class="form-label fw-semibold">Hari</label>
                            <input type="text" name="hari" class="form-control" value="{{ old('hari') }}" id="hariUjian" required readonly></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Mulai</label>
                            <input type="time" name="mulai" class="form-control" value="{{ old('mulai') }}" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Selesai</label>
                            <input type="time" name="selesai" class="form-control" value="{{ old('selesai') }}" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Ruangan <span class="text-danger">*</span></label>
                        <input type="text" name="ruangan" class="form-control" value="{{ old('ruangan') }}" placeholder="Aula Lt.2" required></div>
                    <div class="row g-2 mb-2">
                        <div class="col-5"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ old('semester',1) }}" min="1" max="14" required></div>
                        <div class="col-7"><label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik','2025/2026') }}" required></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold"><i class="fas fa-file-upload me-1 text-primary"></i>Upload Soal <small class="text-muted">(PDF/DOC, maks 10MB)</small></label>
                        <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="text-muted">File akan dikirim ke mahasiswa via notifikasi</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan + Notifikasi</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-file-alt me-2"></i>Daftar Jadwal Ujian ({{ $ujians->total() }})</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari MK / Kelas..." value="{{ request('search') }}"></div>
                    <div class="col-md-2"><select name="jenis_ujian" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="UTS" {{ request('jenis_ujian')==='UTS'?'selected':'' }}>UTS</option>
                        <option value="UAS" {{ request('jenis_ujian')==='UAS'?'selected':'' }}>UAS</option>
                    </select></div>
                    <div class="col-md-2"><input type="text" name="kelas" class="form-control form-control-sm" placeholder="Kelas" value="{{ request('kelas') }}"></div>
                    <div class="col-md-3"><input type="text" name="tahun_akademik" class="form-control form-control-sm" placeholder="2025/2026" value="{{ request('tahun_akademik') }}"></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>MK / Kelas</th><th>Jenis</th><th>Tanggal & Waktu</th><th>Ruangan</th><th>Soal</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($ujians as $u)
                        <tr>
                            <td>
                                <strong>{{ $u->kode_mk }}</strong><br>
                                <small class="text-muted">{{ $u->mata_kuliah }}</small><br>
                                <span class="badge bg-secondary">{{ $u->kelas }}</span>
                            </td>
                            <td><span class="badge {{ $u->jenis_ujian==='UTS'?'bg-warning text-dark':'bg-danger' }} fs-6">{{ $u->jenis_ujian }}</span></td>
                            <td>
                                <strong>{{ $u->hari }}</strong>, {{ \Carbon\Carbon::parse($u->tanggal)->format('d M Y') }}<br>
                                <small>{{ substr($u->mulai,0,5) }} – {{ substr($u->selesai,0,5) }}</small>
                            </td>
                            <td>{{ $u->ruangan }}</td>
                            <td>
                                @if($u->soal)
                                <a href="{{ Storage::disk('public')->url('ujian/'.$u->soal) }}" target="_blank" class="btn btn-xs btn-outline-success btn-sm py-0 px-2">
                                    <i class="fas fa-file-download me-1"></i>Soal
                                </a>
                                @else
                                <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.ujian.edit',$u->id) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.ujian.destroy',$u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ujian?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada jadwal ujian</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $ujians->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
document.getElementById('tglUjian').addEventListener('change', function() {
    const d = new Date(this.value);
    document.getElementById('hariUjian').value = days[d.getDay()];
});
</script>
@endpush
@endsection
