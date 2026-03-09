<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Dosen extends Model
{
    protected $table = 'dosen';
    public $timestamps = false;
    protected $fillable = ['nidn','nppy','nama','password','gelar_depan','gelar_belakang','prodi','jabatan_akademik','status','email','telp','is_active'];
    protected $hidden = ['password'];

    public function mahasiswaBimbingan() { return $this->hasMany(Mahasiswa::class,'dosen_pa_id','id'); }
    public function mataKuliah()         { return $this->hasMany(DosenMatkul::class,'dosen_id','id'); }
    public function jadwalKuliah()       { return $this->hasMany(JadwalKuliah::class,'dosen_id','id'); }

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function getNamaLengkapAttribute(): string
    {
        $n = trim(($this->gelar_depan ? $this->gelar_depan.' ' : '').$this->nama.($this->gelar_belakang ? ', '.$this->gelar_belakang : ''));
        return $n;
    }
}
