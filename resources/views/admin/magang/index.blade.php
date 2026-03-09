@extends('admin.layouts.app')
@section('title','Magang') @section('page-title','Pengajuan Magang')
@section('content')
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="{{ request('nim') }}">
        </div>
        <button class="btn btn-warning" onclick="filterNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        @if(request('nim'))<a href="{{ route('admin.magang.index') }}" class="btn btn-light">Reset</a>@endif
    </div>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-briefcase me-2"></i>Data Pengajuan Magang ({{ $magangList->total() }})</span>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <input type="hidden" name="nim" value="{{ request('nim') }}">
            <div class="col-md-3"><select name="status_magang" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                @foreach(['Menunggu','Diterima','Ditolak'] as $s)
                <option value="{{ $s }}" {{ request('status_magang')===$s?'selected':'' }}>{{ $s }}</option>
                @endforeach
            </select></div>
            <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>NIM / Nama</th><th>Tempat Magang</th><th>Periode</th><th>Status</th><th>Tgl Pengajuan</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($magangList as $m)
                <tr>
                    <td><strong>{{ $m->nim }}</strong><br><small class="text-muted">{{ $m->mahasiswa?->nama }}</small></td>
                    <td>
                        <strong>{{ $m->nama_tempat_magang }}</strong><br>
                        <small class="text-muted">{{ $m->alamat_tempat_magang }}</small>
                    </td>
                    <td>
                        <small>{{ \Carbon\Carbon::parse($m->tgl_mulai)->format('d/m/Y') }}</small> –
                        <small>{{ \Carbon\Carbon::parse($m->tgl_selesai)->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        @php $sc=['Menunggu'=>'warning','Diterima'=>'success','Ditolak'=>'danger']; @endphp
                        <span class="badge bg-{{ $sc[$m->status_magang]??'secondary' }}">{{ $m->status_magang }}</span>
                        @if($m->komentar_prodi)<br><small class="text-muted">{{ Str::limit($m->komentar_prodi,30) }}</small>@endif
                    </td>
                    <td><small>{{ \Carbon\Carbon::parse($m->tgl_pengajuan)->format('d/m/Y') }}</small></td>
                    <td>
                        <button class="btn btn-xs btn-primary btn-sm py-0 px-2"
                            onclick="openMagangModal({{ $m->id }},'{{ $m->status_magang }}','{{ $m->nama_tempat_magang }}')"
                            data-bs-toggle="modal" data-bs-target="#magangModal">
                            <i class="fas fa-edit me-1"></i>Proses
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada data magang</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $magangList->links('pagination::bootstrap-5') }}
    </div>
</div>

<div class="modal fade" id="magangModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="magangForm">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Proses Pengajuan Magang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p class="small text-muted mb-3" id="magangTempat"></p>
                    <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                        <select name="status_magang" id="magangStatus" class="form-select" required>
                            @foreach(['Menunggu','Diterima','Ditolak'] as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Komentar / Catatan</label>
                        <textarea name="komentar_prodi" class="form-control" rows="3" placeholder="Opsional..."></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
function filterNIM() {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    const url = new URL(window.location.href);
    url.searchParams.set('nim', nim);
    window.location.href = url.toString();
}
document.getElementById('nimSearchInput').addEventListener('keydown', e => { if(e.key==='Enter'){e.preventDefault();filterNIM();} });
function openMagangModal(id, status, tempat) {
    document.getElementById('magangForm').action = '/admin/magang/'+id+'/status';
    document.getElementById('magangStatus').value = status;
    document.getElementById('magangTempat').textContent = 'Tempat: ' + tempat;
}
</script>
@endpush
@endsection
