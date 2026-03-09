@extends('admin.layouts.app')
@section('title','Edit Kurikulum') @section('page-title','Edit Kurikulum')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Edit Kurikulum: {{ $kurikulum->kode_mk }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kurikulum.update',$kurikulum->id) }}">
            @csrf @method('PUT')
            <p class="text-muted mb-3">Mata Kuliah: <strong>{{ $kurikulum->mataKuliah?->nama_mk }}</strong></p>
            <div class="row g-3">
                <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                    <input type="number" name="semester" class="form-control" value="{{ old('semester',$kurikulum->semester) }}" min="1" max="14" required></div>
                <div class="col-6"><label class="form-label fw-semibold">Prodi</label>
                    <input type="text" name="prodi" class="form-control" value="{{ old('prodi',$kurikulum->prodi) }}" required></div>
                <div class="col-6"><label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Wajib" {{ old('status',$kurikulum->status)==='Wajib'?'selected':'' }}>Wajib</option>
                        <option value="Pilihan" {{ old('status',$kurikulum->status)==='Pilihan'?'selected':'' }}>Pilihan</option>
                    </select></div>
                <div class="col-6"><label class="form-label fw-semibold">Urutan</label>
                    <input type="number" name="urutan" class="form-control" value="{{ old('urutan',$kurikulum->urutan) }}" min="1"></div>
                <div class="col-4"><label class="form-label fw-semibold">IPK Minimum</label>
                    <input type="number" name="ipk_min" class="form-control" value="{{ old('ipk_min',$kurikulum->ipk_min) }}" step="0.01" min="0" max="4"></div>
                <div class="col-4"><label class="form-label fw-semibold">SKS Minimum</label>
                    <input type="number" name="sks_min" class="form-control" value="{{ old('sks_min',$kurikulum->sks_min) }}" min="0"></div>
                <div class="col-4"><label class="form-label fw-semibold">Grade Minimum</label>
                    <input type="text" name="grade_min" class="form-control" value="{{ old('grade_min',$kurikulum->grade_min) }}" maxlength="2"></div>
                <div class="col-12"><label class="form-label fw-semibold">MK Prasyarat (Kode MK)</label>
                    <input type="text" name="mk_persyaratan" class="form-control" value="{{ old('mk_persyaratan',$kurikulum->mk_persyaratan) }}" placeholder="INF101"></div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
