<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DosenMatkul extends Model {
    protected $table = 'dosen_matkul'; public $timestamps = false;
    protected $fillable = ['dosen_id','kode_mk','tahun_akademik','semester','kelas'];
    public function dosen() { return $this->belongsTo(Dosen::class,'dosen_id'); }
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class,'kode_mk','kode_mk'); }
}
