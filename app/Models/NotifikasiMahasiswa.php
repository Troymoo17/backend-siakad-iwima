<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotifikasiMahasiswa extends Model
{
    protected $table = 'notifikasi_mahasiswa';
    public $timestamps = false;

    protected $fillable = [
        'notifikasi_id', 'nim', 'judul', 'pesan', 'tipe', 'link', 'is_read', 'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function notifikasi()
    {
        return $this->belongsTo(Notifikasi::class, 'notifikasi_id');
    }
}
