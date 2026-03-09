<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SkripsiPengajuan extends Model {
    protected $table = 'skripsi_pengajuan';
    protected $fillable = ['nim','judul','abstrak','jalur','baru_ulang','status','tgl_pengajuan','pembimbing1_id','pembimbing2_id','tgl_proses','komentar_prodi','file_proposal'];
    protected $casts = ['tgl_pengajuan'=>'date','tgl_proses'=>'date','created_at'=>'datetime'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function pembimbing1() { return $this->belongsTo(Dosen::class,'pembimbing1_id'); }
    public function pembimbing2() { return $this->belongsTo(Dosen::class,'pembimbing2_id'); }
}
