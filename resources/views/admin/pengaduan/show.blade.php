@extends('admin.layouts.app')
@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')

@section('content')
<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-envelope-open me-2"></i>Isi Surat Pengaduan</div>
            <div class="card-body">
                <table class="table table-sm mb-3">
                    <tr><td class="text-muted" width="140">Dari</td><td><strong>{{ $surat->nim }}</strong> - {{ $surat->mahasiswa?->nama }}</td></tr>
                    <tr><td class="text-muted">Tujuan</td><td><span class="badge bg-secondary">{{ $surat->tujuan }}</span>
                        @if($surat->dosen) <span class="ms-2">{{ $surat->dosen->nama }}</span> @endif
                    </td></tr>
                    <tr><td class="text-muted">Perihal</td><td><strong>{{ $surat->perihal }}</strong></td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $surat->created_at->format('d M Y H:i') }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        <span class="badge {{ $surat->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $surat->status }}</span>
                    </td></tr>
                </table>
                <div class="bg-light p-3 rounded">
                    <p class="mb-0" style="white-space:pre-wrap">{{ $surat->isi_surat }}</p>
                </div>
            </div>
        </div>

        @if($surat->balasan)
        <div class="card mt-3">
            <div class="card-header bg-success text-white"><i class="fas fa-reply me-2"></i>Balasan</div>
            <div class="card-body">
                <div class="bg-light p-3 rounded mb-2">
                    <p class="mb-0" style="white-space:pre-wrap">{{ $surat->balasan }}</p>
                </div>
                <small class="text-muted">Dibalas oleh {{ $surat->balasan_oleh }} pada {{ $surat->balasan_at?->format('d M Y H:i') }}</small>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        @if($surat->status !== 'Selesai')
        <div class="card">
            <div class="card-header"><i class="fas fa-reply me-2"></i>Balas Pengaduan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pengaduan.balas', $surat->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Balasan</label>
                        <textarea name="balasan" class="form-control" rows="6" required placeholder="Tulis balasan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-paper-plane me-1"></i>Kirim Balasan & Notifikasi
                    </button>
                </form>
            </div>
        </div>
        @endif
        <div class="mt-3">
            <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary w-100">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection
