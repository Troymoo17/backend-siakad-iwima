@extends('admin.layouts.app')
@section('title','Banner Kegiatan') @section('page-title','Banner Kegiatan Akademik')

@push('styles')
<style>
.banner-card {
    border-radius: 12px; overflow: hidden;
    border: 1px solid #dee2e6;
    transition: box-shadow .2s, transform .2s;
    background: #fff;
}
.banner-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.10); transform: translateY(-2px); }
.banner-img { width:100%; height:160px; object-fit:cover; display:block; }
.banner-img-placeholder {
    width:100%; height:160px; background:linear-gradient(135deg,#1a3a5c,#2d6a9f);
    display:flex; align-items:center; justify-content:center;
    font-size:2.5rem; color:rgba(255,255,255,.3);
}
.banner-body { padding: 10px 12px; }
.drag-handle { cursor: grab; color: #adb5bd; }
.drag-handle:active { cursor: grabbing; }
.sortable-ghost { opacity: .4; background: #e9f0ff; border: 2px dashed #0d6efd; }
</style>
@endpush

@section('content')

{{-- Upload Form --}}
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="fas fa-plus-circle text-success"></i>
        <span>Tambah Banner Kegiatan</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                           value="{{ old('judul') }}" required placeholder="Contoh: Wisuda Angkatan 2025">
                    @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        Gambar <span class="text-danger">*</span>
                        <small class="text-muted fw-normal">(JPG/PNG/WEBP, maks 4MB)</small>
                    </label>
                    <input type="file" name="gambar" id="gambarInput"
                           class="form-control @error('gambar') is-invalid @enderror"
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           required onchange="previewGambar(this)">
                    @error('gambar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Urutan</label>
                    <input type="number" name="urutan" class="form-control"
                           value="{{ old('urutan', $banners->count() + 1) }}" min="1">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100" id="submitBtn">
                        <i class="fas fa-upload me-1"></i>Upload
                    </button>
                </div>
            </div>

            {{-- Preview gambar sebelum upload --}}
            <div id="previewArea" class="mt-3 d-none">
                <p class="small fw-semibold text-muted mb-1">Preview:</p>
                <img id="previewImg" src="" alt="preview"
                     class="rounded" style="max-height:120px; max-width:300px; object-fit:cover; border:2px solid #dee2e6;">
            </div>
        </form>
    </div>
</div>

{{-- Daftar Banner --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-images me-2"></i>Daftar Banner ({{ $banners->count() }} gambar)</span>
        <small class="text-muted"><i class="fas fa-grip-vertical me-1"></i>Drag untuk ubah urutan</small>
    </div>
    <div class="card-body">
        @if($banners->count() === 0)
            <div class="text-center py-5 text-muted">
                <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                <p>Belum ada banner. Upload gambar pertama di atas.</p>
            </div>
        @else
        <div class="row g-3" id="bannerGrid">
            @foreach($banners as $banner)
            <div class="col-md-3 col-sm-4 col-6 banner-col" data-id="{{ $banner->id }}">
                <div class="banner-card">
                    {{-- Gambar --}}
                    <img src="{{ Storage::disk('public')->url($banner->file_path) }}"
                         alt="{{ $banner->judul }}" class="banner-img"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="banner-img-placeholder" style="display:none">🖼️</div>

                    <div class="banner-body">
                        {{-- Judul --}}
                        <div class="fw-semibold small text-truncate mb-2" title="{{ $banner->judul }}">
                            {{ $banner->judul }}
                        </div>

                        {{-- Info --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-secondary" style="font-size:.65rem">Urutan {{ $banner->urutan }}</span>
                            {{-- Toggle Aktif --}}
                            <button class="btn btn-sm btn-{{ $banner->is_aktif ? 'success' : 'secondary' }} btn-xs toggle-btn py-0 px-2"
                                    data-id="{{ $banner->id }}" style="font-size:.72rem">
                                <i class="fas fa-{{ $banner->is_aktif ? 'eye' : 'eye-slash' }} me-1"></i>
                                {{ $banner->is_aktif ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-1">
                            <span class="drag-handle me-auto" title="Drag untuk ubah urutan">
                                <i class="fas fa-grip-vertical"></i>
                            </span>
                            <button class="btn btn-xs btn-outline-danger py-0 px-2 hapus-btn"
                                    data-id="{{ $banner->id }}" data-judul="{{ $banner->judul }}"
                                    style="font-size:.72rem">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
// ── Preview sebelum upload ────────────────────────────────────
function previewGambar(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewArea').classList.remove('d-none');
    };
    reader.readAsDataURL(input.files[0]);
}

// ── Submit button loading ─────────────────────────────────────
document.getElementById('uploadForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengupload...';
    btn.disabled  = true;
});

// ── Toggle aktif/nonaktif ─────────────────────────────────────
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        fetch(`/admin/banner/${id}/toggle`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const aktif = d.is_aktif;
                this.className = `btn btn-sm btn-${aktif ? 'success' : 'secondary'} btn-xs toggle-btn py-0 px-2`;
                this.innerHTML = `<i class="fas fa-${aktif ? 'eye' : 'eye-slash'} me-1"></i>${aktif ? 'Aktif' : 'Nonaktif'}`;
                this.style.fontSize = '.72rem';
            }
        });
    });
});

// ── Hapus banner ──────────────────────────────────────────────
document.querySelectorAll('.hapus-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const id    = this.dataset.id;
        const judul = this.dataset.judul;
        if (!confirm(`Hapus banner "${judul}"?`)) return;

        fetch(`/admin/banner/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                this.closest('.banner-col').remove();
            }
        });
    });
});

// ── Drag & drop urutan (SortableJS) ──────────────────────────
const grid = document.getElementById('bannerGrid');
if (grid) {
    Sortable.create(grid, {
        handle: '.drag-handle',
        animation: 180,
        ghostClass: 'sortable-ghost',
        onEnd: function () {
            const urutan = {};
            document.querySelectorAll('.banner-col').forEach((el, i) => {
                urutan[el.dataset.id] = i + 1;
                el.querySelector('.badge').textContent = 'Urutan ' + (i + 1);
            });
            fetch('/admin/banner/urutan', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ urutan })
            });
        }
    });
}
</script>
@endpush
