@extends('admin.layouts.app')
@section('title','Mata Kuliah') @section('page-title','Mata Kuliah')
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2"></i>Tambah Mata Kuliah</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.matakuliah.store') }}">
                    @csrf
                    <div class="mb-3"><label class="form-label fw-semibold">Kode MK <span class="text-danger">*</span></label>
                        <input type="text" name="kode_mk" class="form-control @error('kode_mk') is-invalid @enderror" value="{{ old('kode_mk') }}" placeholder="INF701" required>
                        @error('kode_mk')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label fw-semibold">Nama MK <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mk" class="form-control" value="{{ old('nama_mk') }}" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label fw-semibold">SKS <span class="text-danger">*</span></label>
                            <input type="number" name="sks" class="form-control" value="{{ old('sks',3) }}" min="1" max="6" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                            <input type="number" name="semester" class="form-control" value="{{ old('semester') }}" min="1" max="14" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Prodi</label>
                        <input type="text" name="prodi" class="form-control" value="{{ old('prodi','Informatika') }}"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-book me-2"></i>Daftar Mata Kuliah ({{ $matakuliahs->total() }})</span>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-6"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode / nama MK..." value="{{ request('search') }}"></div>
                    <div class="col-md-3"><input type="number" name="semester" class="form-control form-control-sm" placeholder="Semester..." value="{{ request('semester') }}" min="1" max="14"></div>
                    <div class="col-md-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-search me-1"></i>Cari</button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>Kode</th><th>Nama MK</th><th>SKS</th><th>Sem</th><th>Prodi</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($matakuliahs as $mk)
                        <tr>
                            <td class="fw-semibold text-primary">{{ $mk->kode_mk }}</td>
                            <td>{{ $mk->nama_mk }}</td>
                            <td><span class="badge bg-info">{{ $mk->sks }} SKS</span></td>
                            <td><span class="badge bg-secondary">Sem {{ $mk->semester }}</span></td>
                            <td><small>{{ $mk->prodi }}</small></td>
                            <td>
                                <a href="{{ route('admin.matakuliah.edit',$mk->kode_mk) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.matakuliah.destroy',$mk->kode_mk) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $matakuliahs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
