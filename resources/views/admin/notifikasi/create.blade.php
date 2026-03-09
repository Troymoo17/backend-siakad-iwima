@extends('admin.layouts.app')
@section('title', 'Kirim Notifikasi')
@section('page-title', 'Kirim Notifikasi')

@section('content')
<div class="card">
    <div class="card-header"><i class="fas fa-paper-plane me-2"></i>Form Kirim Notifikasi</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.notifikasi.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Judul Notifikasi <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tipe <span class="text-danger">*</span></label>
                    <select name="tipe" class="form-select" required>
                        @foreach(['info','warning','success','error','pengumuman','akademik','keuangan','krs','nilai','skripsi','umum'] as $t)
                        <option value="{{ $t }}" {{ old('tipe') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                    <textarea name="pesan" class="form-control" rows="4" required>{{ old('pesan') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Target Penerima <span class="text-danger">*</span></label>
                    <select name="target" id="target" class="form-select" required onchange="toggleTarget(this.value)">
                        <option value="all" {{ old('target') === 'all' ? 'selected' : '' }}>Semua Mahasiswa</option>
                        <option value="prodi" {{ old('target') === 'prodi' ? 'selected' : '' }}>Per Prodi</option>
                        <option value="kelas" {{ old('target') === 'kelas' ? 'selected' : '' }}>Per Kelas</option>
                        <option value="personal" {{ old('target') === 'personal' ? 'selected' : '' }}>Personal (NIM)</option>
                    </select>
                </div>
                <div class="col-md-4" id="target_value_group" style="display:none">
                    <label class="form-label fw-semibold" id="target_value_label">Nilai Target</label>
                    <input type="text" name="target_value" id="target_value" class="form-control" value="{{ old('target_value') }}">
                </div>
                <div class="col-md-4" id="nim_personal_group" style="display:none">
                    <label class="form-label fw-semibold">NIM Mahasiswa</label>
                    <input type="text" name="nim_personal" class="form-control" value="{{ old('nim_personal') }}" placeholder="22.240.0007">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Link (opsional)</label>
                    <input type="text" name="link" class="form-control" value="{{ old('link') }}" placeholder="/mahasiswa/krs">
                    <small class="text-muted">Path halaman yang akan dibuka saat notifikasi diklik</small>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>Kirim Notifikasi
                </button>
                <a href="{{ route('admin.notifikasi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleTarget(val) {
    document.getElementById('target_value_group').style.display = (val === 'prodi' || val === 'kelas') ? '' : 'none';
    document.getElementById('nim_personal_group').style.display = val === 'personal' ? '' : 'none';
    if (val === 'prodi') document.getElementById('target_value_label').textContent = 'Nama Prodi';
    if (val === 'kelas') document.getElementById('target_value_label').textContent = 'Kelas (e.g. IF-2022-A)';
}
toggleTarget('{{ old("target", "all") }}');
</script>
@endpush
