<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimbinganSidang extends Model
{
    protected $table    = 'bimbingan_sidang';
    public    $timestamps = false;
    protected $fillable = ['nim','judul_skripsi','pembimbing1_id','pembimbing2_id','penguji1_id','penguji2_id','tanggal_sidang','ruang','nilai','catatan_revisi','status'];
    protected $casts    = ['tanggal_sidang'=>'date'];

    public function mahasiswa()  { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function pembimbing1(){ return $this->belongsTo(Dosen::class,'pembimbing1_id'); }
    public function pembimbing2(){ return $this->belongsTo(Dosen::class,'pembimbing2_id'); }
    public function penguji1()   { return $this->belongsTo(Dosen::class,'penguji1_id'); }
    public function penguji2()   { return $this->belongsTo(Dosen::class,'penguji2_id'); }
}
