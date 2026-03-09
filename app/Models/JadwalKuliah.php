<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliah';
    public $timestamps = false;

    protected $fillable = [
        'kode_mk', 'nama_mk', 'dosen_id', 'kelas', 'hari',
        'jam_mulai', 'jam_selesai', 'ruang', 'jenis',
        'google_classroom_id', 'semester', 'tahun_akademik',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
