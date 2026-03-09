@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1a3a5c,#2d6a9f)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1 opacity-75 small">Mahasiswa Aktif</p>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_mahasiswa'] }}</h3>
                </div>
                <i class="fas fa-users fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#27ae60,#2ecc71)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1 opacity-75 small">Total Dosen</p>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_dosen'] }}</h3>
                </div>
                <i class="fas fa-chalkboard-teacher fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#e74c3c,#c0392b)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1 opacity-75 small">Pengaduan Pending</p>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_pengaduan_belum'] }}</h3>
                </div>
                <i class="fas fa-envelope-open-text fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f39c12,#e67e22)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="mb-1 opacity-75 small">Tagihan Belum Bayar</p>
                    <h4 class="mb-0 fw-bold" style="font-size:1rem">Rp {{ number_format($stats['total_tagihan_belum'],0,',','.') }}</h4>
                </div>
                <i class="fas fa-money-bill fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Distribusi Mahasiswa per Kelas -->
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2 text-primary"></i>Distribusi Mahasiswa per Kelas
            </div>
            <div class="card-body">
                @foreach($mahasiswaPerKelas as $kelas)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">{{ $kelas->kelas }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="width:120px;height:8px">
                            <div class="progress-bar" style="width:{{ min(100, $kelas->total * 10) }}%;background:#1a3a5c"></div>
                        </div>
                        <span class="badge bg-primary">{{ $kelas->total }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pengumuman Terbaru -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-bullhorn me-2 text-warning"></i>Pengumuman Terbaru</span>
                <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($pengumumanTerbaru as $p)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold small">{{ Str::limit($p->judul, 50) }}</span>
                            <span class="badge {{ $p->is_published ? 'bg-success' : 'bg-secondary' }}">
                                {{ $p->is_published ? 'Publish' : 'Draft' }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $p->tanggal_upload }}</small>
                    </div>
                    @empty
                    <div class="list-group-item text-muted text-center py-3">Belum ada pengumuman</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-envelope-open-text me-2 text-danger"></i>Pengaduan Terbaru</span>
                <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-sm btn-outline-danger">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small">
                        <thead class="table-light">
                            <tr><th>Mahasiswa</th><th>Perihal</th><th>Tujuan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @forelse($pengaduanTerbaru as $s)
                            <tr>
                                <td>{{ $s->mahasiswa?->nama ?? $s->nim }}</td>
                                <td>{{ Str::limit($s->perihal, 40) }}</td>
                                <td><span class="badge bg-secondary">{{ $s->tujuan }}</span></td>
                                <td>
                                    <span class="badge {{ $s->status === 'Selesai' ? 'bg-success' : ($s->status === 'Terkirim' ? 'bg-warning text-dark' : 'bg-info') }}">
                                        {{ $s->status }}
                                    </span>
                                </td>
                                <td>{{ $s->created_at ? \Carbon\Carbon::parse($s->created_at)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.pengaduan.show', $s->id) }}" class="btn btn-xs btn-outline-primary btn-sm py-0 px-2">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada pengaduan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection