@extends('admin.layouts.app')
@section('title','Kehadiran') @section('page-title','Manajemen Kehadiran')
@section('content')

{{-- NIM Search Box --}}
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari & Filter by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Masukkan NIM mahasiswa..." value="{{ request('nim') }}">
        </div>
        <button class="btn btn-warning" onclick="filterByNIM()"><i class="fas fa-search me-1"></i>Tampilkan</button>
        @if(request('nim'))<a href="{{ route('admin.kehadiran.index') }}" class="btn btn-light">Reset</a>@endif
    </div>
    @if($rekap)
    <div class="mt-2 row g-2">
        @foreach($rekap as $r)
        @php $pct = $r->total > 0 ? round(($r->hadir/$r->total)*100) : 0; @endphp
        <div class="col-md-6 col-lg-4">
            <div class="alert alert-light py-2 mb-0">
                <strong>{{ $r->kode_matkul }}</strong> – {{ $r->mataKuliah?->nama_mk }}
                <div class="d-flex gap-2 mt-1 flex-wrap small">
                    <span class="badge bg-success">Hadir: {{ $r->hadir }}</span>
                    <span class="badge bg-info">Sakit: {{ $r->sakit }}</span>
                    <span class="badge bg-warning text-dark">Izin: {{ $r->izin }}</span>
                    <span class="badge bg-danger">Alpha: {{ $r->alpha }}</span>
                    <span class="badge bg-primary">{{ $pct }}%</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<div class="row g-3">
    {{-- Input form --}}
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user-check me-2 text-success"></i>Input Kehadiran</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.kehadiran.store') }}">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <div class="d-flex gap-1">
                            <input type="text" name="nim" id="nimInput" class="form-control" value="{{ old('nim', request('nim')) }}" required placeholder="22.240.0001">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="searchNIMKehadiran()"><i class="fas fa-search"></i></button>
                        </div>
                        <div id="nimInfo" class="mt-1"></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah</label>
                        <select name="kode_matkul" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            @foreach($mkList as $mk)
                            <option value="{{ $mk->kode_mk }}" {{ old('kode_matkul')===$mk->kode_mk?'selected':'' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                            @endforeach
                        </select></div>
                    <div class="row g-2 mb-2">
                        <div class="col-5"><label class="form-label fw-semibold">Pertemuan ke-</label>
                            <input type="number" name="pertemuan" class="form-control" value="{{ old('pertemuan',1) }}" min="1" required></div>
                        <div class="col-7"><label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(['Hadir','Tidak Hadir','Sakit','Izin'] as $s)
                                <option value="{{ $s }}" {{ old('status')===$s?'selected':'' }}>{{ $s }}</option>
                                @endforeach
                            </select></div>
                    </div>
                    <div class="mb-2"><label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Opsional"></div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-users me-2 text-info"></i>Input Massal (per Kelas)</div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.kehadiran.bulk-form') }}">
                    <div class="mb-2"><label class="form-label fw-semibold">Mata Kuliah</label>
                        <select name="kode_matkul" class="form-select select2" required>
                            <option value="">-- Pilih MK --</option>
                            @foreach($mkList as $mk)
                            <option value="{{ $mk->kode_mk }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                            @endforeach
                        </select></div>
                    <div class="mb-2"><label class="form-label fw-semibold">Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="IF-2022-A" required></div>
                    <button type="submit" class="btn btn-info w-100 text-white"><i class="fas fa-list-check me-1"></i>Buka Form Massal</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-table me-2"></i>Data Kehadiran ({{ $kehadirans->total() }})</div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <input type="hidden" name="nim" value="{{ request('nim') }}">
                    <div class="col-md-4"><select name="kode_matkul" class="form-select form-select-sm">
                        <option value="">Semua MK</option>
                        @foreach($mkList as $mk)
                        <option value="{{ $mk->kode_mk }}" {{ request('kode_matkul')===$mk->kode_mk?'selected':'' }}>{{ $mk->kode_mk }}</option>
                        @endforeach
                    </select></div>
                    <div class="col-md-3"><select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach(['Hadir','Tidak Hadir','Sakit','Izin'] as $s)
                        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select></div>
                    <div class="col-md-3"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>NIM</th><th>Nama</th><th>MK</th><th>Pertemuan</th><th>Status</th><th>Tanggal</th><th>Hapus</th></tr></thead>
                        <tbody>
                        @forelse($kehadirans as $k)
                        <tr>
                            <td class="fw-semibold">{{ $k->nim }}</td>
                            <td><small>{{ $k->mahasiswa?->nama }}</small></td>
                            <td><small>{{ $k->kode_matkul }}</small></td>
                            <td class="text-center">{{ $k->pertemuan }}</td>
                            <td>
                                @php $colors = ['Hadir'=>'success','Tidak Hadir'=>'danger','Sakit'=>'info','Izin'=>'warning']; @endphp
                                <span class="badge bg-{{ $colors[$k->status] ?? 'secondary' }}">{{ $k->status }}</span>
                            </td>
                            <td><small>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</small></td>
                            <td>
                                <form action="{{ route('admin.kehadiran.destroy',$k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data kehadiran?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $kehadirans->links('pagination::bootstrap-5') }}
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
document.getElementById('nimSearchInput').addEventListener('keydown', e => { if (e.key==='Enter'){e.preventDefault();filterByNIM();} });
function searchNIMKehadiran() {
    const nim = document.getElementById('nimInput').value.trim();
    if (!nim) return;
    fetch('{{ route("admin.kehadiran.search-mhs") }}?nim='+encodeURIComponent(nim), {
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(r=>r.json()).then(data=>{
        const el = document.getElementById('nimInfo');
        if (data.success && data.data) {
            el.innerHTML='<div class="alert alert-info py-1 small mb-0"><i class="fas fa-user me-1"></i><strong>'+data.data.nama+'</strong> – '+data.data.kelas+'</div>';
        } else {
            el.innerHTML='<div class="alert alert-warning py-1 small mb-0">NIM tidak ditemukan</div>';
        }
    });
}
</script>
@endpush
@endsection
