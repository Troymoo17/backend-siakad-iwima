<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PointBook extends Model {
    protected $table = 'point_book'; public $timestamps = false;
    protected $fillable = ['nim','tanggal','nama_kegiatan','poin','keterangan','diinput_oleh'];
    protected $casts = ['tanggal'=>'date'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
}
