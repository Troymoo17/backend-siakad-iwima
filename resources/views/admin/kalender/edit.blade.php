@extends('admin.layouts.app')
@section('title','Edit Kalender') @section('page-title','Edit Kegiatan Akademik')
@section('content')
<div class="card" style="max-width:700px">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Edit: {{ $kalender->judul }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.kalender.update',$kalender->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3"><label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul',$kalender->judul) }}" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi',$kalender->deskripsi) }}</textarea></div>
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label fw-semibold">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        @foreach(['akademik','libur','ujian','event','wisuda','lainnya'] as $k)
                        <option value="{{ $k }}" {{ old('kategori',$kalender->kategori)===$k?'selected':'' }}>{{ ucfirst($k) }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai',$kalender->tanggal_mulai) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai',$kalender->tanggal_selesai) }}"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fas fa-file-upload me-1"></i>Ganti File <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                @if($kalender->file_path)
                <p class="small mb-1"><i class="fas fa-paperclip me-1 text-success"></i>File saat ini:
                    <a href="{{ Storage::disk('public')->url($kalender->file_path) }}" target="_blank">{{ $kalender->file_nama ?? $kalender->file_path }}</a>
                </p>
                @endif
                <input type="file" name="file_kalender" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            </div>

            {{-- Gambar Kegiatan Existing --}}
            @if($kalender->gambar->count())
            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fas fa-images me-1 text-success"></i>Gambar Kegiatan Saat Ini</label>
                <div class="row g-2">
                    @foreach($kalender->gambar->sortBy('urutan') as $gambar)
                    <div class="col-md-3 col-4" id="gambar-item-{{ $gambar->id }}">
                        <div class="position-relative">
                            <img src="{{ Storage::disk('public')->url($gambar->file_path) }}"
                                 class="img-thumbnail w-100" style="height:90px;object-fit:cover"
                                 alt="{{ $gambar->file_nama }}">
                            <button type="button"
                                    class="btn btn-danger btn-xs position-absolute top-0 end-0 m-1 py-0 px-1"
                                    onclick="hapusGambar({{ $gambar->id }})"
                                    title="Hapus gambar ini">
                                <i class="fas fa-times" style="font-size:.65rem"></i>
                            </button>
                        </div>
                        <p class="text-muted text-center mt-1" style="font-size:.65rem">Urutan {{ $gambar->urutan }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upload Gambar Baru --}}
            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="fas fa-plus-circle me-1 text-success"></i>Tambah Gambar Baru <small class="text-muted">(JPG/PNG/WEBP, maks 4MB per gambar)</small></label>
                <input type="file" name="gambar[]" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp" multiple>
                <small class="text-muted">Gambar baru akan ditambahkan (tidak menggantikan yang lama). Bisa pilih lebih dari satu.</small>
            </div>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_published" class="form-check-input" value="1" {{ old('is_published', $kalender->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold">Publish</label>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.kalender.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function hapusGambar(id) {
    if (!confirm('Hapus gambar ini?')) return;
    fetch(`/admin/kalender/gambar/${id}`, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const el = document.getElementById('gambar-item-' + id);
            if (el) el.remove();
        } else {
            alert('Gagal menghapus gambar.');
        }
    });
}
</script>
@endpush
@endsection
