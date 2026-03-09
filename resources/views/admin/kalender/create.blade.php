@extends('admin.layouts.app')
@section('title','Tambah Kalender') @section('page-title','Tambah Kegiatan Akademik')
@section('content')
<div class="card" style="max-width:700px">
    <div class="card-header"><i class="fas fa-calendar-plus me-2"></i>Form Tambah Kegiatan Akademik</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kalender.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3"><label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" required placeholder="Contoh: Ujian Akhir Semester Ganjil 2025/2026">
                @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan tambahan...">{{ old('deskripsi') }}</textarea></div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        @foreach(['akademik','libur','ujian','event','wisuda','lainnya'] as $k)
                        <option value="{{ $k }}" {{ old('kategori')===$k?'selected':'' }}>{{ ucfirst($k) }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fas fa-file-upload me-1 text-primary"></i>Upload File <small class="text-muted">(PDF/JPG/PNG, maks 5MB)</small></label>
                <input type="file" name="file_kalender" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">Bisa berupa brosur, surat edaran, atau dokumen terkait kegiatan</small>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fas fa-images me-1 text-success"></i>Upload Gambar Kegiatan <small class="text-muted">(JPG/PNG/WEBP, maks 4MB per gambar, bisa lebih dari 1)</small></label>
                <input type="file" name="gambar[]" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" multiple>
                <small class="text-muted">Gambar akan ditampilkan di dashboard mahasiswa sebagai slider kegiatan akademik. Bisa pilih lebih dari satu gambar.</small>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublished" value="1" {{ old('is_published', '1') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="isPublished">
                        Publish & Kirim Notifikasi ke Semua Mahasiswa
                    </label>
                </div>
                <small class="text-muted">Jika dicentang, semua mahasiswa aktif akan mendapat notifikasi</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                <a href="{{ route('admin.kalender.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
