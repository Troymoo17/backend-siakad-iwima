@extends('admin.layouts.app')
@section('title','Keuangan') @section('page-title','Tagihan & Pembayaran')
@section('content')

{{-- Stats --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#dc3545,#c82333)">
            <div class="d-flex justify-content-between align-items-center">
                <div><p class="mb-1 small opacity-75">Total Belum Bayar</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($stats['total_belum'],0,',','.') }}</h4>
                    <small class="opacity-75">{{ $stats['count_belum'] }} tagihan</small></div>
                <i class="fas fa-exclamation-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#28a745,#1e7e34)">
            <div class="d-flex justify-content-between align-items-center">
                <div><p class="mb-1 small opacity-75">Total Sudah Lunas</p>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($stats['total_lunas'],0,',','.') }}</h4></div>
                <i class="fas fa-check-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a3a5c,#2d6a9f)">
            <div class="d-flex justify-content-between align-items-center">
                <div><p class="mb-1 small opacity-75">Generate Tagihan Massal</p>
                    <button class="btn btn-warning btn-sm mt-1" onclick="document.getElementById('bulkModal').classList.toggle('d-none')">
                        <i class="fas fa-bolt me-1"></i>Buka Form Massal
                    </button></div>
                <i class="fas fa-cogs fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Modal Inline --}}
<div id="bulkModal" class="d-none mb-3">
    <div class="card border-warning">
        <div class="card-header bg-warning text-dark"><i class="fas fa-bolt me-2"></i>Generate Tagihan Massal</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.keuangan.tagihan.bulk') }}" class="row g-3">
                @csrf
                <div class="col-md-3"><label class="form-label fw-semibold">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" value="2025/2026" required></div>
                <div class="col-md-2"><label class="form-label fw-semibold">Semester</label>
                    <input type="number" name="semester" class="form-control" value="1" min="1" max="14" required></div>
                <div class="col-md-2"><label class="form-label fw-semibold">Jenis</label>
                    <select name="jenis_tagihan" class="form-select" required>
                        @foreach(['UKP','SKS','Denda','Lainnya'] as $j)
                        <option value="{{ $j }}">{{ $j }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-2"><label class="form-label fw-semibold">Nominal (Rp)</label>
                    <input type="number" name="nominal_tagihan" class="form-control" placeholder="3500000" required></div>
                <div class="col-md-2"><label class="form-label fw-semibold">Filter Kelas</label>
                    <input type="text" name="kelas" class="form-control" placeholder="Kosong = Semua"></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" class="form-control"></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="UKP Semester 1 2025/2026"></div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Generate tagihan untuk semua mahasiswa aktif?')">
                        <i class="fas fa-bolt me-1"></i>Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- NIM Search --}}
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM..." value="{{ request('nim') }}">
        </div>
        <button class="btn btn-warning" onclick="filterByNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        @if(request('nim'))<a href="{{ route('admin.keuangan.index') }}" class="btn btn-light">Reset</a>@endif
    </div>
</div>

<div class="row g-3">
    {{-- Tambah Tagihan --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-plus me-2 text-danger"></i>Tambah Tagihan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.keuangan.tagihan.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">NIM</label>
                        <input type="text" name="nim" id="nimTagihan" class="form-control" value="{{ old('nim', request('nim')) }}" required placeholder="22.240.0001">
                        <div id="nimTagihanInfo" class="mt-1"></div></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Jenis Tagihan</label>
                            <select name="jenis_tagihan" class="form-select" required>
                                @foreach(['UKP','SKS','Denda','Lainnya'] as $j)
                                <option value="{{ $j }}" {{ old('jenis_tagihan')===$j?'selected':'' }}>{{ $j }}</option>
                                @endforeach
                            </select></div>
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ old('semester',1) }}" min="1" max="14" required></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Nominal (Rp)</label>
                        <input type="number" name="nominal_tagihan" class="form-control" value="{{ old('nominal_tagihan') }}" placeholder="3500000" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik','2025/2026') }}" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ old('tanggal_jatuh_tempo') }}"></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Deskripsi</label>
                        <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}" placeholder="Keterangan tagihan"></div>
                    <button type="submit" class="btn btn-danger w-100"><i class="fas fa-file-invoice me-1"></i>Buat Tagihan</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-money-bill-wave me-2 text-success"></i>Input Pembayaran</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.keuangan.pembayaran.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">NIM</label>
                        <input type="text" name="nim" class="form-control" value="{{ old('nim', request('nim')) }}" required placeholder="22.240.0001"></div>
                    <div class="mb-2"><label class="form-label fw-semibold">ID Tagihan</label>
                        <input type="number" name="tagihan_id" class="form-control" value="{{ old('tagihan_id') }}" required placeholder="Lihat ID di tabel tagihan"></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Jumlah Bayar (Rp)</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar') }}" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="form-control" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Metode</label>
                        <select name="metode" class="form-select" required>
                            @foreach(['Transfer','Virtual Account','Tunai','Lainnya'] as $m)
                            <option value="{{ $m }}" {{ old('metode')===$m?'selected':'' }}>{{ $m }}</option>
                            @endforeach
                        </select></div>
                    <div class="mb-2"><label class="form-label fw-semibold"><i class="fas fa-camera me-1"></i>Bukti Bayar <small class="text-muted">(Foto/PDF)</small></label>
                        <input type="file" name="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf"></div>
                    <button type="submit" class="btn btn-success w-100"><i class="fas fa-check me-1"></i>Konfirmasi Bayar</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel Tagihan --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-list me-2"></i>Data Tagihan ({{ $tagihans->total() }})</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <input type="hidden" name="nim" value="{{ request('nim') }}">
                    <div class="col-md-3"><select name="status_bayar" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach(['Belum','Lunas','Cicilan'] as $s)
                        <option value="{{ $s }}" {{ request('status_bayar')===$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select></div>
                    <div class="col-md-3"><select name="jenis_tagihan" class="form-select form-select-sm">
                        <option value="">Semua Jenis</option>
                        @foreach(['UKP','SKS','Denda','Lainnya'] as $j)
                        <option value="{{ $j }}" {{ request('jenis_tagihan')===$j?'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select></div>
                    <div class="col-md-2"><input type="number" name="semester" class="form-control form-control-sm" placeholder="Semester" value="{{ request('semester') }}"></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>ID</th><th>NIM & Nama</th><th>Jenis</th><th>Nominal</th><th>Sem</th><th>Status</th><th>Jatuh Tempo</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($tagihans as $t)
                        <tr class="{{ $t->status_bayar==='Belum' && $t->tanggal_jatuh_tempo && $t->tanggal_jatuh_tempo < date('Y-m-d') ? 'table-danger' : '' }}">
                            <td><small class="text-muted">#{{ $t->id }}</small></td>
                            <td>
                                <strong>{{ $t->nim }}</strong><br>
                                <small class="text-muted">{{ $t->mahasiswa?->nama }}</small>
                            </td>
                            <td><span class="badge bg-secondary">{{ $t->jenis_tagihan }}</span><br>
                                <small class="text-muted">{{ $t->deskripsi }}</small></td>
                            <td class="fw-semibold">Rp {{ number_format($t->nominal_tagihan,0,',','.') }}</td>
                            <td>{{ $t->semester }}</td>
                            <td>
                                @php $colors = ['Belum'=>'danger','Lunas'=>'success','Cicilan'=>'warning']; @endphp
                                <span class="badge bg-{{ $colors[$t->status_bayar]??'secondary' }}">{{ $t->status_bayar }}</span>
                                @if($t->pembayaran->count())
                                <br><small class="text-success">Bayar: Rp {{ number_format($t->pembayaran->sum('jumlah_bayar'),0,',','.') }}</small>
                                @endif
                            </td>
                            <td>
                                @if($t->tanggal_jatuh_tempo)
                                <small class="{{ $t->tanggal_jatuh_tempo < date('Y-m-d') && $t->status_bayar==='Belum' ? 'text-danger fw-bold' : 'text-muted' }}">
                                    {{ \Carbon\Carbon::parse($t->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                </small>
                                @else <small class="text-muted">-</small> @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.keuangan.tagihan.destroy',$t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tagihan?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada tagihan</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $tagihans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function filterByNIM() {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    const url = new URL(window.location.href);
    url.searchParams.set('nim', nim);
    window.location.href = url.toString();
}
document.getElementById('nimSearchInput').addEventListener('keydown', e => { if(e.key==='Enter'){e.preventDefault();filterByNIM();}});
document.getElementById('nimTagihan').addEventListener('blur', function() {
    const nim = this.value.trim(); if (!nim) return;
    fetch('{{ route("admin.keuangan.search-mhs") }}?nim='+encodeURIComponent(nim), {
        headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(r=>r.json()).then(data=>{
        const el=document.getElementById('nimTagihanInfo');
        if(data.success&&data.data) el.innerHTML='<div class="alert alert-info py-1 small mb-0">'+data.data.nama+' – '+data.data.kelas+'</div>';
        else el.innerHTML='<div class="alert alert-warning py-1 small mb-0">NIM tidak ditemukan</div>';
    });
});
</script>
@endpush
@endsection
