<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 'krs';
    public $timestamps = false;

    protected $fillable = ['nim', 'semester', 'tahun_akademik', 'kode_mk', 'status', 'disetujui_dosen'];

    protected $casts = ['created_at' => 'datetime'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }
}
