<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model {
    protected $table    = 'pengajuan_magang';
    public    $timestamps = false;
    protected $fillable = ['nim','jenis_tempat_magang','alamat','nama_tempat_magang','kota_kabupaten_magang','baru_ulang','rencana_mulai','rencana_selesai','status_magang','tgl_pengajuan','tgl_proses','komentar_prodi','surat_pengantar'];
    protected $casts    = ['rencana_mulai'=>'date','rencana_selesai'=>'date','tgl_pengajuan'=>'date'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
}
