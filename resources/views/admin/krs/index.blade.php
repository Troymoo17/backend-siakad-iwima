@extends('admin.layouts.app')
@section('title','KRS Mahasiswa') @section('page-title','KRS Mahasiswa')
@section('content')

{{-- NIM Search --}}
<div class="nim-search-box mb-3">
    <div class="d-flex gap-2 align-items-end flex-wrap">
        <div class="flex-grow-1">
            <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
            <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM lalu tekan Enter..." value="{{ request('nim') }}">
        </div>
        <button type="button" class="btn btn-warning" onclick="filterByNIM()"><i class="fas fa-search me-1"></i>Cari</button>
        @if(request('nim'))
        <a href="{{ route('admin.krs.index') }}" class="btn btn-light">Reset</a>
        @endif
    </div>
    <div id="nimInfo" class="mt-2">
        @if(request('nim') && $krsList->total() > 0)
        @php $firstMhs = $krsList->first()?->mahasiswa; @endphp
        @if($firstMhs)
        <div class="alert alert-light py-2 mb-0"><i class="fas fa-user-graduate me-2 text-primary"></i>
            <strong>{{ $firstMhs->nama }}</strong> &bull; {{ $firstMhs->kelas }} &bull; Semester {{ $firstMhs->semester_sekarang }}</div>
        @endif
        @endif
    </div>
</div>

<div class="row g-3">
    {{-- Form Tambah KRS --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-plus me-2 text-success"></i>Tambah KRS</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.krs.store') }}" id="krsForm">
                    @csrf
                    <div class="mb-2"><label class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="nimKrs" class="form-control" value="{{ old('nim', request('nim')) }}" required placeholder="22.240.0001"></div>
                    <div id="nimKrsInfo" class="mb-2"></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><label class="form-label fw-semibold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ old('semester',1) }}" min="1" max="14" required></div>
                        <div class="col-6"><label class="form-label fw-semibold">Tahun Akademik</label>
                            <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik','2025/2026') }}" required></div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Pilih Mata Kuliah <span class="text-danger">*</span></label>
                        <select name="kode_mk[]" class="form-select select2" multiple required>
                            @foreach(\App\Models\MataKuliah::orderBy('semester')->orderBy('kode_mk')->get() as $mk)
                            <option value="{{ $mk->kode_mk }}">Sem {{ $mk->semester }} | {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Ctrl+click untuk memilih lebih dari satu</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-1"><i class="fas fa-save me-1"></i>Simpan KRS</button>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-check-double me-2 text-success"></i>Setujui Semua KRS</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.krs.approve-all') }}">
                    @csrf
                    <div class="mb-2"><input type="text" name="nim" class="form-control form-control-sm" placeholder="NIM" required></div>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><input type="number" name="semester" class="form-control form-control-sm" placeholder="Semester" required></div>
                        <div class="col-6"><input type="text" name="tahun_akademik" class="form-control form-control-sm" placeholder="2025/2026" required></div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-check me-1"></i>Approve Semua</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabel KRS --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clipboard-list me-2"></i>Data KRS ({{ $krsList->total() }})</span>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-3"><input type="text" name="nim" class="form-control form-control-sm" placeholder="Filter NIM..." value="{{ request('nim') }}"></div>
                    <div class="col-md-2"><input type="number" name="semester" class="form-control form-control-sm" placeholder="Sem" value="{{ request('semester') }}"></div>
                    <div class="col-md-3"><input type="text" name="tahun_akademik" class="form-control form-control-sm" placeholder="2025/2026" value="{{ request('tahun_akademik') }}"></div>
                    <div class="col-md-2"><select name="disetujui" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="1" {{ request('disetujui')==='1'?'selected':'' }}>Disetujui</option>
                        <option value="0" {{ request('disetujui')==='0'?'selected':'' }}>Pending</option>
                    </select></div>
                    <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light"><tr><th>NIM</th><th>Mahasiswa</th><th>MK</th><th>Sem</th><th>TA</th><th>Status</th><th>Aksi</th></tr></thead>
                        <tbody>
                        @forelse($krsList as $krs)
                        <tr>
                            <td class="fw-semibold">{{ $krs->nim }}</td>
                            <td><small>{{ $krs->mahasiswa?->nama }}</small></td>
                            <td>
                                <strong>{{ $krs->kode_mk }}</strong>
                                <br><small class="text-muted">{{ $krs->mataKuliah?->nama_mk }}</small>
                            </td>
                            <td>{{ $krs->semester }}</td>
                            <td><small>{{ $krs->tahun_akademik }}</small></td>
                            <td>
                                @if($krs->disetujui_dosen)
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if(!$krs->disetujui_dosen)
                                <form action="{{ route('admin.krs.approve',$krs->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-xs btn-success btn-sm py-0 px-2" title="Approve"><i class="fas fa-check"></i></button>
                                </form>
                                @endif
                                <form action="{{ route('admin.krs.destroy',$krs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus KRS ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada data KRS</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $krsList->links('pagination::bootstrap-5') }}
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
document.getElementById('nimSearchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); filterByNIM(); }
});
// Search NIM untuk form tambah
document.getElementById('nimKrs').addEventListener('blur', function() {
    const nim = this.value.trim();
    if (!nim) return;
    fetch('{{ route("admin.krs.search-mhs") }}?nim=' + encodeURIComponent(nim), {
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}
    }).then(r=>r.json()).then(data=>{
        const el = document.getElementById('nimKrsInfo');
        if (data.success && data.data) {
            el.innerHTML = '<div class="alert alert-info py-1 mb-0 small"><i class="fas fa-user me-1"></i><strong>'+data.data.nama+'</strong> - '+data.data.kelas+' Sem '+data.data.semester_sekarang+'</div>';
        } else {
            el.innerHTML = '<div class="alert alert-warning py-1 mb-0 small">NIM tidak ditemukan</div>';
        }
    });
});
</script>
@endpush
@endsection
