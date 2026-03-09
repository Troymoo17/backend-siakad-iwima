<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model {
    protected $table = 'pinjaman'; public $timestamps = false;
    protected $fillable = ['nim','tanggal_pinjam','tanggal_kembali','tanggal_kembali_aktual','nama_buku','kode_buku','status_pinjaman','denda'];
    protected $casts = ['tanggal_pinjam'=>'date','tanggal_kembali'=>'date','denda'=>'float'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
}
