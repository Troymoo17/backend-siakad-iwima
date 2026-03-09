@extends('admin.layouts.app')
@section('title','Edit MK') @section('page-title','Edit Mata Kuliah')
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-edit me-2"></i>Edit: {{ $mk->kode_mk }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.matakuliah.update',$mk->kode_mk) }}">
                    @csrf @method('PUT')
                    <div class="mb-3"><label class="form-label fw-semibold">Nama MK <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mk" class="form-control" value="{{ old('nama_mk',$mk->nama_mk) }}" required></div>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><label class="form-label fw-semibold">SKS</label>
                            <input type="number" name="sks" class="form-control" value="{{ old('sks',$mk->sks) }}" min="1" max="6"></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ old('semester',$mk->semester) }}" min="1" max="14"></div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-semibold">Prodi</label>
                        <input type="text" name="prodi" class="form-control" value="{{ old('prodi',$mk->prodi) }}"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi',$mk->deskripsi) }}</textarea></div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                        <a href="{{ route('admin.matakuliah.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-chalkboard-teacher me-2"></i>Assign Dosen Pengampu</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.matakuliah.assign-dosen',$mk->kode_mk) }}">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">Dosen</label>
                        <select name="dosen_id" class="form-select select2" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }} ({{ $d->nidn }})</option>
                            @endforeach
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="2025/2026" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ $mk->semester }}" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="IF-2022-A" required></div>
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-plus me-1"></i>Tambah Pengampu</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-list me-2"></i>Dosen Pengampu ({{ $pengampus->count() }})</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Dosen</th><th>Kelas</th><th>Tahun Akademik</th><th>Hapus</th></tr></thead>
                    <tbody>
                    @forelse($pengampus as $dm)
                    <tr>
                        <td>{{ $dm->dosen?->nama }}</td>
                        <td>{{ $dm->kelas }}</td>
                        <td>{{ $dm->tahun_akademik }}</td>
                        <td>
                            <form action="{{ route('admin.matakuliah.remove-dosen',$dm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengampu?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada pengampu</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
