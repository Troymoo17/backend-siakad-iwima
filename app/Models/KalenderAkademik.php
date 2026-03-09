<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model
{
    protected $table      = 'kalender_akademik';
    public    $timestamps = false;

    protected $fillable = [
        'judul','deskripsi','tanggal_mulai','tanggal_selesai',
        'kategori','file_path','file_nama','is_published','created_by',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'is_published'    => 'boolean',
    ];

    // Relasi ke gambar-gambar kegiatan
    public function gambar()
    {
        return $this->hasMany(GambarKegiatan::class, 'kalender_id')->orderBy('urutan');
    }
}