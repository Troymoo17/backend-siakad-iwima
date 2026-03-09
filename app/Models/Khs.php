<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    protected $table = 'khs';
    public $timestamps = false;
    protected $fillable = ['nim', 'semester', 'tahun_akademik', 'total_sks', 'total_sks_kumulatif', 'ips', 'ipk'];
    protected $casts = ['ips' => 'float', 'ipk' => 'float'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
