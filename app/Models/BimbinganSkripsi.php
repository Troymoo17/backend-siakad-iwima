<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BimbinganSkripsi extends Model {
    protected $table = 'bimbingan_skripsi';
    protected $fillable = ['nim','dosen_id','tanggal','bab','uraian','catatan_dosen','status'];
    protected $casts = ['tanggal'=>'date','created_at'=>'datetime','updated_at'=>'datetime'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function dosen() { return $this->belongsTo(Dosen::class,'dosen_id'); }
}
