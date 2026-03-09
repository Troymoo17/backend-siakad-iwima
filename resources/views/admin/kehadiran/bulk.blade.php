@extends('admin.layouts.app')
@section('title','Input Kehadiran Massal') @section('page-title','Input Kehadiran Massal')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="fas fa-users me-2 text-info"></i>
            Input Kehadiran: <strong>{{ $mk->nama_mk }}</strong> – Kelas <strong>{{ $request->kelas }}</strong>
        </span>
        <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card-body">
        @if($mahasiswas->isEmpty())
            <div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>Tidak ada mahasiswa aktif di kelas {{ $request->kelas }}</div>
        @else
        <form method="POST" action="{{ route('admin.kehadiran.bulk-store') }}">
            @csrf
            <input type="hidden" name="kode_matkul" value="{{ $mk->kode_mk }}">
            <input type="hidden" name="kelas" value="{{ $request->kelas }}">
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pertemuan ke- <span class="text-danger">*</span></label>
                    <input type="number" name="pertemuan" class="form-control" value="{{ $existingPertemuan + 1 }}" min="1" required>
                    <small class="text-muted">Pertemuan terakhir: {{ $existingPertemuan }}</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-5 d-flex align-items-end gap-2">
                    <button type="button" class="btn btn-success btn-sm" onclick="setAll('Hadir')"><i class="fas fa-check-double me-1"></i>Hadir Semua</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="setAll('Tidak Hadir')"><i class="fas fa-times me-1"></i>Alpha Semua</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light text-center">
                        <tr><th>#</th><th>NIM</th><th>Nama Mahasiswa</th>
                            <th><span class="text-success">Hadir</span></th>
                            <th><span class="text-info">Sakit</span></th>
                            <th><span class="text-warning">Izin</span></th>
                            <th><span class="text-danger">Alpha</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($mahasiswas as $i => $mhs)
                    <tr id="row_{{ $mhs->nim }}">
                        <td class="text-center text-muted">{{ $i+1 }}</td>
                        <td class="fw-semibold">{{ $mhs->nim }}</td>
                        <td>{{ $mhs->nama }}</td>
                        @foreach(['Hadir','Sakit','Izin','Tidak Hadir'] as $s)
                        @php $colors = ['Hadir'=>'success','Sakit'=>'info','Izin'=>'warning','Tidak Hadir'=>'danger']; @endphp
                        <td class="text-center">
                            <div class="form-check d-flex justify-content-center">
                                <input type="radio" name="kehadiran[{{ $mhs->nim }}]"
                                       value="{{ $s }}"
                                       class="form-check-input status-radio"
                                       data-nim="{{ $mhs->nim }}"
                                       data-status="{{ $s }}"
                                       {{ $s === 'Hadir' ? 'checked' : '' }}
                                       onclick="highlightRow('{{ $mhs->nim }}','{{ $s }}')">
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Kehadiran ({{ $mahasiswas->count() }} mahasiswa)</button>
                <a href="{{ route('admin.kehadiran.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
        @endif
    </div>
</div>
@push('scripts')
<script>
const rowColors = {Hadir:'table-success',Sakit:'table-info',Izin:'table-warning','Tidak Hadir':'table-danger'};
function highlightRow(nim, status) {
    const row = document.getElementById('row_'+nim);
    row.className = rowColors[status] || '';
}
function setAll(status) {
    document.querySelectorAll('.status-radio').forEach(r => {
        if (r.dataset.status === status) {
            r.checked = true;
            highlightRow(r.dataset.nim, status);
        }
    });
}
// Init highlight
document.querySelectorAll('.status-radio:checked').forEach(r => highlightRow(r.dataset.nim, r.dataset.status));
</script>
@endpush
@endsection
