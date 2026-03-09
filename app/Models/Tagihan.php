<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';
    public $timestamps = false;
    protected $fillable = [
        'nim', 'semester', 'tahun_akademik', 'jenis_tagihan',
        'deskripsi', 'nominal_tagihan', 'tanggal_jatuh_tempo', 'status_bayar',
    ];
    protected $casts = [
        'nominal_tagihan' => 'float',
        'tanggal_jatuh_tempo' => 'date',
        'created_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id');
    }
}
