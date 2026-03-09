<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model {
    protected $table = 'kehadiran'; public $timestamps = false;
    protected $fillable = ['nim','kode_matkul','jadwal_id','pertemuan','status','tanggal','keterangan'];
    protected $casts = ['tanggal' => 'date'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class,'kode_matkul','kode_mk'); }
}
