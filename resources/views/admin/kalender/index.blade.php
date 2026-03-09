@extends('admin.layouts.app')
@section('title','Kalender Akademik') @section('page-title','Kalender Akademik')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small">Total: {{ $kalenders->total() }} kegiatan</span>
    <a href="{{ route('admin.kalender.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Tambah Kegiatan</a>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-calendar-check me-2"></i>Daftar Kegiatan Akademik</span>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><select name="kategori" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                @foreach(['akademik','libur','ujian','event','wisuda','lainnya'] as $k)
                <option value="{{ $k }}" {{ request('kategori')===$k?'selected':'' }}>{{ ucfirst($k) }}</option>
                @endforeach
            </select></div>
            <div class="col-md-2"><select name="is_published" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="1" {{ request('is_published')==='1'?'selected':'' }}>Dipublish</option>
                <option value="0" {{ request('is_published')==='0'?'selected':'' }}>Draft</option>
            </select></div>
            <div class="col-md-2"><button class="btn btn-secondary btn-sm w-100"><i class="fas fa-filter"></i></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Judul</th><th>Kategori</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Gambar</th><th>File</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse($kalenders as $k)
                <tr>
                    <td><strong>{{ $k->judul }}</strong><br><small class="text-muted">{{ Str::limit($k->deskripsi, 60) }}</small></td>
                    <td>
                        @php $kColors=['akademik'=>'primary','libur'=>'success','ujian'=>'danger','event'=>'info','wisuda'=>'warning','lainnya'=>'secondary']; @endphp
                        <span class="badge bg-{{ $kColors[$k->kategori]??'secondary' }}">{{ ucfirst($k->kategori) }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d M Y') }}</td>
                    <td>{{ $k->tanggal_selesai ? \Carbon\Carbon::parse($k->tanggal_selesai)->format('d M Y') : '-' }}</td>
                    <td>
                        @if($k->gambar && $k->gambar->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($k->gambar->take(3) as $gm)
                            <a href="{{ Storage::disk('public')->url($gm->file_path) }}" target="_blank">
                                <img src="{{ Storage::disk('public')->url($gm->file_path) }}"
                                     style="width:40px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #ddd;"
                                     onerror="this.style.display='none'">
                            </a>
                            @endforeach
                            @if($k->gambar->count() > 3)
                            <span class="badge bg-secondary align-self-center">+{{ $k->gambar->count()-3 }}</span>
                            @endif
                        </div>
                        @else <span class="text-muted small">-</span> @endif
                    </td>
                        @if($k->file_path)
                        <a href="{{ Storage::disk('public')->url($k->file_path) }}" target="_blank" class="btn btn-xs btn-outline-success btn-sm py-0 px-2">
                            <i class="fas fa-download me-1"></i>{{ $k->file_nama ?? 'File' }}
                        </a>
                        @else <span class="text-muted small">-</span> @endif
                    </td>
                    <td><span class="badge {{ $k->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $k->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>
                        <a href="{{ route('admin.kalender.edit',$k->id) }}" class="btn btn-xs btn-outline-warning btn-sm py-0 px-2"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.kalender.destroy',$k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan?')">
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
        {{ $kalenders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
