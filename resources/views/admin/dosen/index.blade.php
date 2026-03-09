@extends('admin.layouts.app')
@section('title','Data Dosen') @section('page-title','Data Dosen')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Daftar Dosen</span>
        <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Dosen</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari NIDN / Nama / Email..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['Tetap','Tidak Tetap','Luar Biasa'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="is_active" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="1" {{ request('is_active')==='1'?'selected':'' }}>Aktif</option>
                    <option value="0" {{ request('is_active')==='0'?'selected':'' }}>Non-aktif</option>
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-search"></i></button></div>
            <div class="col-md-2"><a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>NIDN</th><th>Nama</th><th>Jabatan</th><th>Status</th><th>Email</th><th>Bimbingan</th><th>Aktif</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($dosens as $d)
                <tr>
                    <td class="fw-semibold">{{ $d->nidn }}</td>
                    <td>
                        {{ $d->gelar_depan }} <strong>{{ $d->nama }}</strong>{{ $d->gelar_belakang ? ', '.$d->gelar_belakang : '' }}
                        <br><small class="text-muted">{{ $d->prodi }}</small>
                    </td>
                    <td>{{ $d->jabatan_akademik }}</td>
                    <td><span class="badge {{ $d->status==='Tetap'?'bg-success':'bg-secondary' }}">{{ $d->status }}</span></td>
                    <td><small>{{ $d->email }}</small></td>
                    <td><span class="badge bg-info">{{ $d->mahasiswa_bimbingan_count }} mhs</span></td>
                    <td><span class="badge {{ $d->is_active?'bg-success':'bg-danger' }}">{{ $d->is_active?'Aktif':'Non-aktif' }}</span></td>
                    <td>
                        <a href="{{ route('admin.dosen.show',$d->id) }}" class="btn btn-xs btn-outline-info btn-sm py-0 px-2"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.dosen.edit',$d->id) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.dosen.destroy',$d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus dosen ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">Total: {{ $dosens->total() }} dosen</small>
            {{ $dosens->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
