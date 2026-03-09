<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model {
    protected $table = 'jadwal_ujian'; 
    public $timestamps = false;
    protected $fillable = ['kode_mk','mata_kuliah','kelas','dosen_id','jenis_ujian','tanggal','hari','mulai','selesai','ruangan','soal','semester','tahun_akademik'];
    protected $casts = ['tanggal'=>'date'];

    // Accessors untuk menyamakan nama kolom dengan yang dipakai controller
    public function getJamMulaiAttribute()  { return $this->mulai; }
    public function getJamSelesaiAttribute(){ return $this->selesai; }
    public function getRuangAttribute()     { return $this->ruangan; }

    public function dosen()     { return $this->belongsTo(Dosen::class,'dosen_id'); }
    public function mataKuliah(){ return $this->belongsTo(MataKuliah::class,'kode_mk','kode_mk'); }
}
