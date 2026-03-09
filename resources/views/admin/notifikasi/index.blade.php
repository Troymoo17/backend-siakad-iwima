@extends('admin.layouts.app')
@section('title', 'Manajemen Notifikasi')
@section('page-title', 'Manajemen Notifikasi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bell me-2"></i>Daftar Notifikasi</span>
        <a href="{{ route('admin.notifikasi.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-paper-plane me-1"></i>Kirim Notifikasi
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover small mb-0">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Tipe</th><th>Target</th><th>Tanggal</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($notifikasi as $n)
                    <tr>
                        <td class="fw-semibold">{{ $n->judul }}</td>
                        <td>
                            <span class="badge badge-tipe bg-{{ $n->tipe === 'akademik' ? 'primary' : ($n->tipe === 'keuangan' ? 'warning text-dark' : ($n->tipe === 'nilai' ? 'success' : 'info')) }}">
                                {{ $n->tipe }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $n->target }}</span>
                            @if($n->target_value) <small class="text-muted">({{ $n->target_value }})</small> @endif
                        </td>
                        <td>{{ $n->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.notifikasi.destroy', $n->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada notifikasi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $notifikasi->links('pagination::bootstrap-5') }}</div>
    </div>
</div>
@endsection
