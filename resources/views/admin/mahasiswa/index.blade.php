@extends('admin.layouts.app')
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users me-2"></i>Daftar Mahasiswa</span>
        <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari NIM / Nama / Email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="kelas" class="form-control form-control-sm" placeholder="Kelas..." value="{{ request('kelas') }}">
            </div>
            <div class="col-md-2">
                <select name="status_aktif" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['Aktif','Cuti','Keluar','Lulus'] as $s)
                    <option value="{{ $s }}" {{ request('status_aktif') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary btn-sm w-100">
                    <i class="fas fa-search me-1"></i>Cari
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover small">
                <thead class="table-light">
                    <tr>
                        <th>NIM</th><th>Nama</th><th>Kelas</th><th>Semester</th>
                        <th>Dosen PA</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $m)
                    <tr>
                        <td class="fw-semibold">{{ $m->nim }}</td>
                        <td>{{ $m->nama }}</td>
                        <td>{{ $m->kelas }}</td>
                        <td><span class="badge bg-info">Sem {{ $m->semester_sekarang }}</span></td>
                        <td>{{ $m->dosenPA?->nama ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $m->status_aktif === 'Aktif' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $m->status_aktif }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.mahasiswa.show', $m->nim) }}" class="btn btn-xs btn-outline-info btn-sm py-0 px-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.mahasiswa.edit', $m->nim) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.mahasiswa.destroy', $m->nim) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus mahasiswa {{ $m->nama }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3 text-muted">Data tidak ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">Menampilkan {{ $mahasiswas->firstItem() }}-{{ $mahasiswas->lastItem() }} dari {{ $mahasiswas->total() }} data</small>
            {{ $mahasiswas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
