<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KrsSetting extends Model
{
    protected $table    = 'krs_setting';
    protected $fillable = ['nim','semester','tahun_akademik','is_aktif','created_by'];
    protected $casts    = ['is_aktif'=>'boolean','created_at'=>'datetime','updated_at'=>'datetime'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}