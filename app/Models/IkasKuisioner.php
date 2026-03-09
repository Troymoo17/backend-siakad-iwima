<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IkasKuisioner extends Model
{
    protected $table    = 'ikas_kuisioner';
    public $timestamps  = false;
    protected $fillable = ['nim','id_staff','status','semester','tahun_akademik','tanggal_isi'];
    protected $casts    = ['tanggal_isi'=>'datetime'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function staff()     { return $this->belongsTo(Staff::class,'id_staff','id_staff'); }
}