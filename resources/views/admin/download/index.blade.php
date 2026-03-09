@extends('admin.layouts.app')
@section('title','Download Materi') @section('page-title','Upload & Download Materi')
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header" style="background:linear-gradient(135deg,#1a3a5c,#2d6a9f);color:#fff">
                <i class="fas fa-cloud-upload-alt me-2"></i>Upload File Materi / Dokumen
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.download.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama / Keterangan File <span class="text-danger">*</span></label>
                        <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                               value="{{ old('keterangan') }}" required
                               placeholder="Contoh: Silabus Pemrograman Web Semester 5">
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach(['Silabus','Modul','Materi Kuliah','Formulir','Panduan','Pengumuman','Template','Lainnya'] as $k)
                            <option value="{{ $k }}" {{ old('kategori')===$k?'selected':'' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-file-upload me-1 text-primary"></i>
                            Pilih File <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png" required>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>
                            Format: PDF, Word, Excel, PPT, ZIP, Gambar &bull; Maks: <strong>20MB</strong>
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload me-1"></i>Upload File
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-info-circle me-2 text-info"></i>Panduan Upload</div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>PDF, Word (.doc, .docx)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Excel (.xls, .xlsx)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>PowerPoint (.ppt, .pptx)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Arsip (.zip, .rar)</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gambar (.jpg, .jpeg, .png)</li>
                    <li class="mt-3 text-muted"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Maksimum 20MB per file</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-folder-open me-2"></i>Daftar File Tersedia ({{ $downloads->total() }})</span>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-5"><input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama file..." value="{{ request('search') }}"></div>
                    <div class="col-md-4">
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="">Semua Kategori</option>
                            @foreach(['Silabus','Modul','Materi Kuliah','Formulir','Panduan','Pengumuman','Template','Lainnya'] as $k)
                            <option value="{{ $k }}" {{ request('kategori')===$k?'selected':'' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-search me-1"></i>Cari</button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr><th>Nama / Keterangan</th><th>Kategori</th><th>Tipe File</th><th>Tanggal Upload</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        @forelse($downloads as $d)
                        @php
                            $ext = pathinfo($d->file_path, PATHINFO_EXTENSION);
                            $iconMap = ['pdf'=>'fa-file-pdf text-danger','doc'=>'fa-file-word text-primary','docx'=>'fa-file-word text-primary',
                                'xls'=>'fa-file-excel text-success','xlsx'=>'fa-file-excel text-success',
                                'ppt'=>'fa-file-powerpoint text-warning','pptx'=>'fa-file-powerpoint text-warning',
                                'zip'=>'fa-file-archive text-secondary','rar'=>'fa-file-archive text-secondary',
                                'jpg'=>'fa-file-image text-info','jpeg'=>'fa-file-image text-info','png'=>'fa-file-image text-info'];
                            $icon = $iconMap[$ext] ?? 'fa-file text-muted';
                        @endphp
                        <tr>
                            <td>
                                <i class="fas {{ $icon }} me-2 fa-lg"></i>
                                <strong>{{ $d->keterangan }}</strong>
                            </td>
                            <td>
                                @if($d->kategori)
                                <span class="badge bg-info text-dark">{{ $d->kategori }}</span>
                                @else <span class="text-muted small">-</span> @endif
                            </td>
                            <td><span class="badge bg-secondary text-uppercase">{{ strtoupper($ext) }}</span></td>
                            <td>
                                <small class="text-muted">
                                    {{ $d->created_at ? \Carbon\Carbon::parse($d->created_at)->format('d M Y H:i') : '-' }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ Storage::disk('public')->url($d->file_path) }}" target="_blank"
                                   class="btn btn-xs btn-success btn-sm py-0 px-2" title="Download">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                                <form action="{{ route('admin.download.destroy',$d->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus file ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-3x mb-2 d-block opacity-25"></i>
                                Belum ada file yang diupload
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $downloads->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
