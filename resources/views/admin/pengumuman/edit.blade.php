@extends('admin.layouts.app')
@section('title','Edit Pengumuman') @section('page-title','Edit Pengumuman')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Edit Pengumuman: {{ Str::limit($pengumuman->judul,50) }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengumuman.update',$pengumuman->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul',$pengumuman->judul) }}" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="isian" class="form-control" rows="8" required>{{ old('isian',$pengumuman->isian) }}</textarea></div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Upload</label>
                    <input type="date" name="tanggal_upload" class="form-control" value="{{ old('tanggal_upload',$pengumuman->tanggal_upload) }}" required></div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold"><i class="fas fa-paperclip me-1"></i>Ganti Lampiran <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    @if($pengumuman->file_path)
                    <p class="small mb-1"><i class="fas fa-file me-1 text-primary"></i>Saat ini: 
                        <a href="{{ Storage::disk('public')->url($pengumuman->file_path) }}" target="_blank">{{ $pengumuman->file_nama }}</a>
                    </p>
                    @endif
                    <input type="file" name="file_pengumuman" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published',$pengumuman->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold">Published</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
