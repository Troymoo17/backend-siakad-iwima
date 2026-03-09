<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadMateri extends Model
{
    protected $table = 'download_materi';
    public $timestamps = false;
    protected $fillable = ['keterangan','file_path','kategori','created_by'];
    protected $casts = ['created_at' => 'datetime'];
}
