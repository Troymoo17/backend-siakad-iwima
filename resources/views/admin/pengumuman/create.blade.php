@extends('admin.layouts.app')
@section('title','Buat Pengumuman') @section('page-title','Buat Pengumuman')
@section('content')
<div class="card" style="max-width:760px">
    <div class="card-header"><i class="fas fa-bullhorn me-2"></i>Form Pengumuman Baru</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.pengumuman.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3"><label class="form-label fw-semibold">Judul Pengumuman <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required placeholder="Masukkan judul pengumuman...">
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label fw-semibold">Isi Pengumuman <span class="text-danger">*</span></label>
                <textarea name="isian" class="form-control @error('isian') is-invalid @enderror" rows="8" required placeholder="Tulis isi pengumuman di sini...">{{ old('isian') }}</textarea>
                @error('isian')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Upload <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_upload" class="form-control" value="{{ old('tanggal_upload', date('Y-m-d')) }}" required></div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold"><i class="fas fa-paperclip me-1 text-primary"></i>Lampiran File <small class="text-muted">(opsional, PDF/Word/Gambar, maks 10MB)</small></label>
                    <input type="file" name="file_pengumuman" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublish" value="1" {{ old('is_published','1') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="isPublish">
                        Publish & Kirim Notifikasi ke Semua Mahasiswa
                    </label>
                </div>
                <small class="text-muted">Jika dicentang, semua mahasiswa aktif akan mendapat notifikasi otomatis</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan & Publish</button>
                <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
