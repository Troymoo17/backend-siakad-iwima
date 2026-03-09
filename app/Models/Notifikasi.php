<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    public $timestamps = false;

    protected $fillable = [
        'judul', 'pesan', 'tipe', 'target', 'target_value',
        'file_path', 'link', 'created_by', 'is_active',
    ];

    protected $casts = ['created_at' => 'datetime'];
}
