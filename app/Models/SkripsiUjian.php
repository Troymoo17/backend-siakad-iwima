<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkripsiUjian extends Model
{
    protected $table    = 'skripsi_ujian';
    public    $timestamps = false;
    protected $fillable = ['nim','judul_skripsi','pembimbing1_id','pembimbing2_id','ipk_terakhir','jumlah_sks','sertifikasi','status','tgl_pengajuan','komentar_prodi'];
    protected $casts    = ['tgl_pengajuan'=>'date'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function pembimbing1(){ return $this->belongsTo(Dosen::class,'pembimbing1_id'); }
    public function pembimbing2(){ return $this->belongsTo(Dosen::class,'pembimbing2_id'); }
}
