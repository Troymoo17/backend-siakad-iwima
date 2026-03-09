@extends('admin.layouts.app')
@section('title','Edit Jadwal Ujian') @section('page-title','Edit Jadwal Ujian')
@section('content')
<div class="card" style="max-width:680px">
    <div class="card-header"><i class="fas fa-edit me-2"></i>Edit Jadwal Ujian: {{ $ujian->mata_kuliah }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ujian.update',$ujian->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Mata Kuliah</label>
                    <select name="kode_mk" class="form-select select2" required>
                        @foreach($mkList as $mk)
                        <option value="{{ $mk->kode_mk }}" {{ old('kode_mk',$ujian->kode_mk)===$mk->kode_mk?'selected':'' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Dosen Pengawas</label>
                    <select name="dosen_id" class="form-select select2">
                        <option value="">-- Tidak ada --</option>
                        @foreach($dosenList as $d)
                        <option value="{{ $d->id }}" {{ old('dosen_id',$ujian->dosen_id)==$d->id?'selected':'' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Kelas</label>
                    <input type="text" name="kelas" class="form-control" value="{{ old('kelas',$ujian->kelas) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Jenis Ujian</label>
                    <select name="jenis_ujian" class="form-select" required>
                        <option value="UTS" {{ old('jenis_ujian',$ujian->jenis_ujian)==='UTS'?'selected':'' }}>UTS</option>
                        <option value="UAS" {{ old('jenis_ujian',$ujian->jenis_ujian)==='UAS'?'selected':'' }}>UAS</option>
                    </select></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Ruangan</label>
                    <input type="text" name="ruangan" class="form-control" value="{{ old('ruangan',$ujian->ruangan) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal',$ujian->tanggal) }}" required id="tglEdit"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Hari</label>
                    <input type="text" name="hari" class="form-control" value="{{ old('hari',$ujian->hari) }}" id="hariEdit" required readonly></div>
                <div class="col-md-4"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Jam Mulai</label>
                    <input type="time" name="mulai" class="form-control" value="{{ old('mulai', substr($ujian->mulai,0,5)) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Jam Selesai</label>
                    <input type="time" name="selesai" class="form-control" value="{{ old('selesai', substr($ujian->selesai,0,5)) }}" required></div>
                <div class="col-md-4"></div>
                <div class="col-md-3"><label class="form-label fw-semibold">Semester</label>
                    <input type="number" name="semester" class="form-control" value="{{ old('semester',$ujian->semester) }}" required></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Tahun Akademik</label>
                    <input type="text" name="tahun_akademik" class="form-control" value="{{ old('tahun_akademik',$ujian->tahun_akademik) }}" required></div>
                <div class="col-12">
                    <label class="form-label fw-semibold"><i class="fas fa-file-upload me-1"></i>Upload Soal Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    @if($ujian->soal)
                    <p class="small mb-1"><i class="fas fa-paperclip me-1 text-success"></i>File saat ini: 
                        <a href="{{ Storage::disk('public')->url('ujian/'.$ujian->soal) }}" target="_blank">{{ $ujian->soal }}</a>
                    </p>
                    @endif
                    <input type="file" name="soal" class="form-control" accept=".pdf,.doc,.docx">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('admin.ujian.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
document.getElementById('tglEdit').addEventListener('change', function() {
    document.getElementById('hariEdit').value = days[new Date(this.value).getDay()];
});
</script>
@endpush
@endsection
