<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarKegiatan extends Model
{
    protected $table    = 'gambar_kegiatan';
    public    $timestamps = false;
    protected $fillable = ['kalender_id','file_path','file_nama','urutan'];
    protected $casts    = ['created_at'=>'datetime'];

    protected $appends  = ['file_url'];

    public function getFileUrlAttribute(): string
    {
        return url('storage/'.$this->file_path);
    }

    public function kalender()
    {
        return $this->belongsTo(KalenderAkademik::class, 'kalender_id');
    }
}