<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table    = 'pengumuman';
    public $timestamps  = false;
    protected $fillable = ['judul', 'isian', 'file_path', 'file_nama', 'tanggal_upload', 'is_published', 'created_by'];
    protected $casts    = ['tanggal_upload' => 'date', 'created_at' => 'datetime'];
    protected $appends  = ['file_url'];

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? url('storage/' . $this->file_path) : null;
    }
}
