<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table      = 'staff';
    public    $timestamps = false;
    protected $primaryKey = 'id_staff';
    protected $fillable   = ['nama_staf','bagian','jabatan'];
}