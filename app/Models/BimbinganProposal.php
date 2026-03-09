<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimbinganProposal extends Model
{
    protected $table    = 'bimbingan_proposal';
    public    $timestamps = false;
    protected $fillable = ['nim','dosen_id','judul_proposal','tanggal_sidang','nilai','catatan_revisi','status'];
    protected $casts    = ['tanggal_sidang'=>'date'];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class,'nim','nim'); }
    public function dosen()     { return $this->belongsTo(Dosen::class,'dosen_id'); }
}
