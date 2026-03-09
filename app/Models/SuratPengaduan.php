<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPengaduan extends Model
{
    protected $table    = 'surat_pengaduan';
    public    $timestamps = false;
    protected $fillable = ['nim','tujuan','dosen_id','perihal','isi_surat','file_lampiran','status','balasan','balasan_oleh','balasan_at'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function dosen()     { return $this->belongsTo(Dosen::class,'dosen_id'); }
}
