<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['kode_mk', 'nama_mk', 'sks', 'semester', 'prodi', 'deskripsi'];

    public function dosen()
    {
        return $this->hasMany(DosenMatkul::class, 'kode_mk', 'kode_mk');
    }

    public function kurikulum()
    {
        return $this->hasOne(Kurikulum::class, 'kode_mk', 'kode_mk');
    }
}
