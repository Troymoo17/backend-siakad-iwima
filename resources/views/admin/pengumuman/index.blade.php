@extends('admin.layouts.app')
@section('title','Pengumuman') @section('page-title','Manajemen Pengumuman')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-bullhorn me-2"></i>Daftar Pengumuman ({{ $pengumuman->total() }})</span>
        <a href="{{ route('admin.pengumuman.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Buat Pengumuman</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Judul</th><th>Tanggal</th><th>Lampiran</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($pengumuman as $p)
                <tr>
                    <td>
                        <strong>{{ $p->judul }}</strong>
                        <br><small class="text-muted">{{ Str::limit($p->isian, 80) }}</small>
                    </td>
                    <td><small>{{ \Carbon\Carbon::parse($p->tanggal_upload)->format('d M Y') }}</small></td>
                    <td>
                        @if($p->file_path)
                        <a href="{{ Storage::disk('public')->url($p->file_path) }}" target="_blank" class="btn btn-xs btn-outline-success btn-sm py-0 px-2">
                            <i class="fas fa-download me-1"></i>{{ Str::limit($p->file_nama ?? 'File', 20) }}
                        </a>
                        @else <span class="text-muted small">-</span> @endif
                    </td>
                    <td><span class="badge {{ $p->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $p->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>
                        <a href="{{ route('admin.pengumuman.edit',$p->id) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.pengumuman.destroy',$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada pengumuman</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $pengumuman->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
