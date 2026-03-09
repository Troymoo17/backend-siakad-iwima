<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerKegiatan extends Model
{
    protected $table    = 'banner_kegiatan';
    protected $fillable = ['judul', 'file_path', 'file_nama', 'urutan', 'is_aktif', 'created_by'];
    protected $casts    = ['is_aktif' => 'boolean'];
    protected $appends  = ['file_url'];

    public function getFileUrlAttribute(): string
    {
        return url('storage/' . $this->file_path);
    }
}
