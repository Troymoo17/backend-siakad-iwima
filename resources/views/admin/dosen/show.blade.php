@extends('admin.layouts.app')
@section('title','Detail Dosen') @section('page-title','Detail Dosen')
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px">
                    <i class="fas fa-chalkboard-teacher text-white fa-2x"></i>
                </div>
                <h5 class="fw-bold">{{ $dosen->gelar_depan }} {{ $dosen->nama }}{{ $dosen->gelar_belakang ? ', '.$dosen->gelar_belakang : '' }}</h5>
                <p class="text-muted mb-1">{{ $dosen->nidn }}</p>
                <span class="badge {{ $dosen->is_active?'bg-success':'bg-danger' }}">{{ $dosen->is_active?'Aktif':'Non-aktif' }}</span>
                <table class="table table-sm text-start mt-3">
                    <tr><td class="text-muted">Prodi</td><td>{{ $dosen->prodi }}</td></tr>
                    <tr><td class="text-muted">Jabatan</td><td>{{ $dosen->jabatan_akademik }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>{{ $dosen->status }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $dosen->email ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td>{{ $dosen->telp ?? '-' }}</td></tr>
                </table>
                <a href="{{ route('admin.dosen.edit',$dosen->id) }}" class="btn btn-warning btn-sm w-100"><i class="fas fa-edit me-1"></i>Edit</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-users me-2"></i>Mahasiswa Bimbingan ({{ $dosen->mahasiswaBimbingan->count() }})</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>NIM</th><th>Nama</th><th>Kelas</th><th>Semester</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($dosen->mahasiswaBimbingan as $m)
                        <tr><td>{{ $m->nim }}</td><td>{{ $m->nama }}</td><td>{{ $m->kelas }}</td>
                            <td>{{ $m->semester_sekarang }}</td>
                            <td><span class="badge {{ $m->status_aktif==='Aktif'?'bg-success':'bg-secondary' }}">{{ $m->status_aktif }}</span></td></tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-2">Belum ada mahasiswa bimbingan</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-book me-2"></i>Mata Kuliah Diampu</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Kode MK</th><th>Nama MK</th><th>Kelas</th><th>Tahun Akademik</th></tr></thead>
                        <tbody>
                        @forelse($dosen->mataKuliah as $dm)
                        <tr><td>{{ $dm->kode_mk }}</td><td>{{ $dm->mataKuliah?->nama_mk }}</td><td>{{ $dm->kelas }}</td><td>{{ $dm->tahun_akademik }}</td></tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-2">Belum ada MK</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
