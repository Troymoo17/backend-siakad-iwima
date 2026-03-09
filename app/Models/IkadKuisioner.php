<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkadKuisioner extends Model
{
    protected $table    = 'ikad_kuisioner';
    public $timestamps  = false;
    protected $fillable = ['nim','kode_mk','dosen_id','status','semester','tahun_akademik','tanggal_isi'];
    protected $casts    = ['tanggal_isi'=>'datetime'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function mataKuliah(){ return $this->belongsTo(MataKuliah::class,'kode_mk','kode_mk'); }
    public function dosen()     { return $this->belongsTo(Dosen::class,'dosen_id'); }
}