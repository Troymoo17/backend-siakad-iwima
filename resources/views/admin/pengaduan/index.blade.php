@extends('admin.layouts.app')
@section('title', 'Pengaduan Mahasiswa')
@section('page-title', 'Pengaduan Mahasiswa')

@section('content')
<div class="card">
    <div class="card-header"><i class="fas fa-envelope-open-text me-2"></i>Daftar Pengaduan</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover small mb-0">
                <thead class="table-light">
                    <tr><th>Mahasiswa</th><th>Perihal</th><th>Tujuan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($pengaduan as $s)
                    <tr>
                        <td><strong>{{ $s->nim }}</strong><br><small>{{ $s->mahasiswa?->nama }}</small></td>
                        <td>{{ Str::limit($s->perihal, 50) }}</td>
                        <td><span class="badge bg-secondary">{{ $s->tujuan }}</span></td>
                        <td>
                            <span class="badge {{ $s->status === 'Selesai' ? 'bg-success' : ($s->status === 'Terkirim' ? 'bg-warning text-dark' : 'bg-info') }}">
                                {{ $s->status }}
                            </span>
                        </td>
                        <td>{{ $s->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.pengaduan.show', $s->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-3 text-muted">Tidak ada pengaduan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $pengaduan->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection
