<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    public $timestamps = false;
    protected $fillable = ['tagihan_id', 'nim', 'tanggal_bayar', 'jumlah_bayar', 'metode', 'bukti_bayar', 'keterangan'];
    protected $casts = ['tanggal_bayar' => 'date', 'jumlah_bayar' => 'float', 'created_at' => 'datetime'];

    public function tagihan() { return $this->belongsTo(Tagihan::class, 'tagihan_id'); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'nim', 'nim'); }
}
