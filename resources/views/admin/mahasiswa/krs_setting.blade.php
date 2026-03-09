@extends('admin.layouts.app')
@section('title','Setting KRS') @section('page-title','Setting KRS Mahasiswa')

@section('content')

{{-- Form Buat Setting KRS Baru --}}
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-cog me-2 text-primary"></i>Buka / Kelola KRS Mahasiswa</div>
    <div class="card-body">
        {{-- NIM Search --}}
        <div class="nim-search-box mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-white fw-semibold mb-1"><i class="fas fa-search me-1"></i>Cari Mahasiswa by NIM</label>
                    <div class="input-group">
                        <input type="text" id="nimSearchInput" class="form-control" placeholder="Ketik NIM...">
                        <button class="btn btn-warning" onclick="cariMahasiswa()"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-8" id="nimInfoArea"></div>
            </div>
        </div>

        {{-- Form KRS Setting --}}
        <form method="POST" action="{{ route('admin.krs-setting.store') }}" id="krsSettingForm">
            @csrf
            <input type="hidden" name="nim" id="nimInput">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">NIM Mahasiswa <span class="text-danger">*</span></label>
                    <input type="text" id="nimDisplay" class="form-control" disabled placeholder="Akan terisi otomatis">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Semester KRS <span class="text-danger">*</span></label>
                    <input type="number" name="semester" id="semesterInput" class="form-control" min="1" max="14" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                    <input type="text" name="tahun_akademik" class="form-control" value="{{ date('Y') }}/{{ date('Y')+1 }}" required placeholder="2025/2026">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check form-switch mt-1">
                        <input type="checkbox" name="is_aktif" class="form-check-input" id="isAktif" value="1" checked>
                        <label class="form-check-label fw-semibold" for="isAktif">Aktifkan KRS</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i>Simpan</button>
                </div>
            </div>

            {{-- Daftar Kurikulum Semester --}}
            <div id="kurikulumArea" class="mt-3 d-none">
                <label class="form-label fw-semibold">
                    <i class="fas fa-book me-1 text-primary"></i>Kurikulum Semester <span id="kurikulumSemLabel"></span>
                    <small class="text-muted ms-2">(opsional — untuk referensi MK yang akan diambil)</small>
                </label>
                <div id="kurikulumList" class="row g-2"></div>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Daftar Setting KRS --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i>Daftar Setting KRS</span>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="nim" class="form-control form-control-sm" placeholder="Filter NIM..." value="{{ request('nim') }}" style="width:160px">
            <button class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
            @if(request('nim'))<a href="{{ route('admin.krs-setting.index') }}" class="btn btn-light btn-sm">Reset</a>@endif
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover small mb-0">
                <thead class="table-light">
                    <tr>
                        <th>NIM</th><th>Nama</th><th>Semester</th><th>Tahun Akademik</th>
                        <th>Status KRS</th><th>Dibuat</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settings as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->nim }}</td>
                        <td>{{ $s->mahasiswa?->nama ?? '-' }}</td>
                        <td><span class="badge bg-info">Sem {{ $s->semester }}</span></td>
                        <td>{{ $s->tahun_akademik }}</td>
                        <td>
                            <button class="btn btn-sm btn-{{ $s->is_aktif ? 'success' : 'secondary' }} btn-xs toggle-btn"
                                    data-id="{{ $s->id }}" data-aktif="{{ $s->is_aktif }}"
                                    title="{{ $s->is_aktif ? 'KRS Terbuka — klik untuk tutup' : 'KRS Tertutup — klik untuk buka' }}">
                                <i class="fas fa-{{ $s->is_aktif ? 'unlock' : 'lock' }} me-1"></i>
                                {{ $s->is_aktif ? 'Terbuka' : 'Tertutup' }}
                            </button>
                        </td>
                        <td>{{ $s->created_at->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('admin.krs-setting.destroy', $s->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus setting KRS ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada setting KRS</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $settings->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
// Cari mahasiswa by NIM
function cariMahasiswa() {
    const nim = document.getElementById('nimSearchInput').value.trim();
    if (!nim) return;
    const sem = document.getElementById('semesterInput').value || '';

    fetch(`{{ route('admin.krs-setting.search-mhs') }}?nim=${nim}&semester=${sem}`, {
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) {
            document.getElementById('nimInfoArea').innerHTML =
                '<div class="alert alert-danger py-2 mb-0 text-white"><i class="fas fa-times me-1"></i>' + d.message + '</div>';
            return;
        }
        const mhs = d.data;
        document.getElementById('nimInput').value = mhs.nim;
        document.getElementById('nimDisplay').value = mhs.nim;
        document.getElementById('semesterInput').value = d.next_semester;
        document.getElementById('nimInfoArea').innerHTML = `
            <div class="alert alert-light py-2 mb-0">
                <i class="fas fa-user-graduate me-2 text-primary"></i>
                <strong>${mhs.nama}</strong> &bull; ${mhs.kelas} &bull; Semester ${mhs.semester_sekarang}
                &bull; Prodi: ${mhs.prodi}
            </div>`;

        // Tampilkan kurikulum
        if (d.kurikulum && d.kurikulum.length > 0) {
            const area = document.getElementById('kurikulumArea');
            document.getElementById('kurikulumSemLabel').textContent = d.next_semester;
            let html = '';
            d.kurikulum.forEach(mk => {
                html += `<div class="col-md-4">
                    <div class="border rounded p-2 small bg-light">
                        <div class="fw-semibold">${mk.nama_mk}</div>
                        <div class="text-muted">${mk.kode_mk} &bull; ${mk.sks} SKS
                            <span class="badge bg-${mk.status === 'Wajib' ? 'primary' : 'warning text-dark'} ms-1">${mk.status}</span>
                        </div>
                    </div></div>`;
            });
            document.getElementById('kurikulumList').innerHTML = html;
            area.classList.remove('d-none');
        } else {
            document.getElementById('kurikulumArea').classList.add('d-none');
        }
    })
    .catch(() => {
        document.getElementById('nimInfoArea').innerHTML =
            '<div class="alert alert-danger py-2 mb-0">Terjadi kesalahan jaringan.</div>';
    });
}

// Toggle aktif/nonaktif KRS
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        fetch(`/admin/krs-setting/${id}/toggle`, {
            method: 'PATCH',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const aktif = d.is_aktif;
                this.className = `btn btn-sm btn-${aktif ? 'success' : 'secondary'} btn-xs toggle-btn`;
                this.dataset.aktif = aktif ? '1' : '0';
                this.innerHTML = `<i class="fas fa-${aktif ? 'unlock' : 'lock'} me-1"></i>${aktif ? 'Terbuka' : 'Tertutup'}`;
                this.title = aktif ? 'KRS Terbuka — klik untuk tutup' : 'KRS Tertutup — klik untuk buka';
            }
        });
    });
});

// Update kurikulum otomatis saat semester input berubah
document.getElementById('semesterInput').addEventListener('change', function() {
    const nim = document.getElementById('nimInput').value;
    if (nim) cariMahasiswa();
});

// Enter di NIM search
document.getElementById('nimSearchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); cariMahasiswa(); }
});
</script>
@endpush
@endsection
