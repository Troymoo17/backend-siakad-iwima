@extends('admin.layouts.app')
@section('title','Skripsi') @section('page-title','Manajemen Skripsi')
@section('content')

{{-- NIM Search --}}
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="{{ request('nim') }}">
        </div>
        <button class="btn btn-warning" onclick="filterNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        @if(request('nim'))<a href="{{ route('admin.skripsi.index') }}" class="btn btn-light">Reset</a>@endif
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="skripsiTab">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-pengajuan">Pengajuan Skripsi ({{ $pengajuans->total() }})</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-bimbingan">Bimbingan ({{ $bimbingans->total() }})</a></li>
                </ul>
            </div>
            <div class="card-body tab-content">
                {{-- Tab Pengajuan --}}
                <div class="tab-pane fade show active" id="tab-pengajuan">
                    <form method="GET" class="row g-2 mb-3">
                        <input type="hidden" name="nim" value="{{ request('nim') }}">
                        <div class="col-md-3"><select name="status" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            @foreach(['Diajukan','Disetujui','Ditolak'] as $s)
                            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ $s }}</option>
                            @endforeach
                        </select></div>
                        <div class="col-md-2"><button class="btn btn-secondary btn-sm"><i class="fas fa-filter"></i></button></div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light"><tr><th>NIM / Nama</th><th>Judul Skripsi</th><th>Bidang</th><th>Status</th><th>Pembimbing</th><th>Tgl Pengajuan</th><th>Aksi</th></tr></thead>
                            <tbody>
                            @forelse($pengajuans as $p)
                            <tr>
                                <td><strong>{{ $p->nim }}</strong><br><small class="text-muted">{{ $p->mahasiswa?->nama }}</small></td>
                                <td style="max-width:200px"><small>{{ $p->judul }}</small></td>
                                <td><small>{{ $p->bidang_ilmu }}</small></td>
                                <td>
                                    @php $sc=['Diajukan'=>'warning','Disetujui'=>'success','Ditolak'=>'danger']; @endphp
                                    <span class="badge bg-{{ $sc[$p->status]??'secondary' }}">{{ $p->status }}</span>
                                </td>
                                <td>
                                    <small>1: {{ $p->pembimbing1?->nama ?? '-' }}</small><br>
                                    <small>2: {{ $p->pembimbing2?->nama ?? '-' }}</small>
                                </td>
                                <td><small>{{ \Carbon\Carbon::parse($p->tgl_pengajuan)->format('d/m/Y') }}</small></td>
                                <td>
                                    <button class="btn btn-xs btn-primary btn-sm py-0 px-2" onclick="openStatusModal({{ $p->id }},'{{ $p->status }}','{{ $p->judul }}')" data-bs-toggle="modal" data-bs-target="#statusModal">
                                        <i class="fas fa-edit me-1"></i>Proses
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada pengajuan</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $pengajuans->links('pagination::bootstrap-5') }}
                </div>

                {{-- Tab Bimbingan --}}
                <div class="tab-pane fade" id="tab-bimbingan">
                    <form method="GET" class="row g-2 mb-3">
                        <input type="hidden" name="nim_bimb" value="{{ request('nim') }}">
                        <div class="col-md-3"><button class="btn btn-secondary btn-sm"><i class="fas fa-sync"></i></button></div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light"><tr><th>NIM / Nama</th><th>Dosen</th><th>Bab</th><th>Tanggal</th><th>Status</th><th>Catatan Mhs</th><th>Aksi</th></tr></thead>
                            <tbody>
                            @forelse($bimbingans as $b)
                            <tr>
                                <td><strong>{{ $b->nim }}</strong><br><small>{{ $b->mahasiswa?->nama }}</small></td>
                                <td><small>{{ $b->dosen?->nama }}</small></td>
                                <td><span class="badge bg-info">{{ $b->bab }}</span></td>
                                <td><small>{{ \Carbon\Carbon::parse($b->tanggal)->format('d/m/Y') }}</small></td>
                                <td>
                                    @php $sb=['Menunggu'=>'warning','Diterima'=>'success','Revisi'=>'danger']; @endphp
                                    <span class="badge bg-{{ $sb[$b->status]??'secondary' }}">{{ $b->status }}</span>
                                </td>
                                <td><small>{{ Str::limit($b->catatan_mahasiswa, 50) }}</small></td>
                                <td>
                                    <button class="btn btn-xs btn-warning btn-sm py-0 px-2" onclick="openBimbModal({{ $b->id }},'{{ $b->status }}')" data-bs-toggle="modal" data-bs-target="#bimbModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $bimbingans->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Status Pengajuan --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="statusForm">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Proses Pengajuan Skripsi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p class="small text-muted mb-3" id="modalJudul"></p>
                    <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required id="modalStatus">
                            <option value="Diajukan">Diajukan</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Pembimbing 1</label>
                        <select name="pembimbing1_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Pembimbing 2</label>
                        <select name="pembimbing2_id" class="form-select select2">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($dosenList as $d)
                            <option value="{{ $d->id }}">{{ $d->nama }}</option>
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

{{-- Modal Bimbingan --}}
<div class="modal fade" id="bimbModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="bimbForm">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Update Status Bimbingan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required id="bimbStatus">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Revisi">Revisi</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Catatan Dosen</label>
                        <textarea name="catatan_dosen" class="form-control" rows="3" placeholder="Masukkan catatan/feedback..."></textarea></div>
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
function openStatusModal(id, status, judul) {
    document.getElementById('statusForm').action = '/admin/skripsi/'+id+'/status';
    document.getElementById('modalStatus').value = status;
    document.getElementById('modalJudul').textContent = 'Judul: ' + judul;
}
function openBimbModal(id, status) {
    document.getElementById('bimbForm').action = '/admin/skripsi/bimbingan/'+id;
    document.getElementById('bimbStatus').value = status;
}
</script>
@endpush
@endsection
