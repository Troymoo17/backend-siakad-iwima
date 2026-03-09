<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenMahasiswa extends Model
{
    protected $table = 'token_mahasiswa';
    public $timestamps = false;
    protected $fillable = ['nim', 'token', 'device_info', 'ip_address', 'expired_at', 'is_active'];
    protected $casts = ['expired_at' => 'datetime', 'created_at' => 'datetime'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
