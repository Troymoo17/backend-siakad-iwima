@extends('admin.layouts.app')
@section('title','Perpustakaan') @section('page-title','Manajemen Pinjaman Perpustakaan')
@section('content')
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="{{ request('nim') }}">
        </div>
        <button class="btn btn-warning" onclick="filterNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        @if(request('nim'))<a href="{{ route('admin.perpustakaan.index') }}" class="btn btn-light">Reset</a>@endif
    </div>
</div>
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-success"></i>Input Pinjaman Buku</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.perpustakaan.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <div class="d-flex gap-1">
                            <input type="text" name="nim" id="nimPinjam" class="form-control" value="{{ old('nim', request('nim')) }}" required placeholder="22.240.0001">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="cariNIM()"><i class="fas fa-search"></i></button>
                        </div>
                        <div id="nimPinjamInfo" class="mt-1"></div></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Kode Buku</label>
                        <input type="text" name="kode_buku" class="form-control" value="{{ old('kode_buku') }}" placeholder="INF-001"></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Nama Buku <span class="text-danger">*</span></label>
                        <input type="text" name="nama_buku" class="form-control" value="{{ old('nama_buku') }}" required placeholder="Judul buku..."></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Batas Kembali</label>
                        <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+14 days'))) }}" required></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-book me-1"></i>Pinjamkan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-book-open me-2"></i>Data Pinjaman ({{ $pinjamans->total() }})</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <input type="hidden" name="nim" value="{{ request('nim') }}">
                    <div class="col-md-4"><select name="status_pinjaman" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach(['Dipinjam','Sudah Kembali','Terlambat'] as $s)
                        <option value="{{ $s }}" {{ request('status_pinjaman')===$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>NIM / Nama</th><th>Buku</th><th>Pinjam</th><th>Batas Kembali</th><th>Status</th><th>Denda</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($pinjamans as $p)
                        <tr class="{{ $p->status_pinjaman==='Terlambat' ? 'table-danger' : ($p->status_pinjaman==='Dipinjam' && $p->tanggal_kembali < date('Y-m-d') ? 'table-warning' : '') }}">
                            <td><strong>{{ $p->nim }}</strong><br><small>{{ $p->mahasiswa?->nama }}</small></td>
                            <td><strong>{{ $p->nama_buku }}</strong><br><small class="text-muted">{{ $p->kode_buku }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</small></td>
                            <td><small class="{{ $p->tanggal_kembali < date('Y-m-d') && $p->status_pinjaman==='Dipinjam' ? 'text-danger fw-bold' : '' }}">
                                {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}
                            </small></td>
                            <td>
                                @php $sc=['Dipinjam'=>'primary','Sudah Kembali'=>'success','Terlambat'=>'danger']; @endphp
                                <span class="badge bg-{{ $sc[$p->status_pinjaman]??'secondary' }}">{{ $p->status_pinjaman }}</span>
                            </td>
                            <td>{{ $p->denda > 0 ? 'Rp '.number_format($p->denda,0,',','.') : '-' }}</td>
                            <td>
                                @if($p->status_pinjaman === 'Dipinjam')
                                <form action="{{ route('admin.perpustakaan.kembalikan',$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Kembalikan buku?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-xs btn-success btn-sm py-0 px-2"><i class="fas fa-undo me-1"></i>Kembali</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.perpustakaan.destroy',$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data pinjaman</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $pinjamans->links('pagination::bootstrap-5') }}
            </div>
        </div>
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
function cariNIM() {
    const nim = document.getElementById('nimPinjam').value.trim(); if (!nim) return;
    fetch('{{ route("admin.perpustakaan.search-mhs") }}?nim='+encodeURIComponent(nim), {
        headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(r=>r.json()).then(data=>{
        const el=document.getElementById('nimPinjamInfo');
        if(data.success&&data.data) el.innerHTML='<div class="alert alert-info py-1 small mb-0">'+data.data.nama+' – '+data.data.kelas+'</div>';
        else el.innerHTML='<div class="alert alert-warning py-1 small mb-0">NIM tidak ditemukan</div>';
    });
}
</script>
@endpush
@endsection
