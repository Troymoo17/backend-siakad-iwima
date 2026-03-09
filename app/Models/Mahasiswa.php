<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim', 'password', 'nama', 'nik', 'prodi', 'program', 'kelas', 'angkatan',
        'dosen_pa_id', 'alamat', 'kota', 'rtrw', 'kodepos', 'provinsi',
        'kewarganegaraan', 'jenis_kelamin', 'agama', 'status_pernikahan',
        'tempat_lahir', 'tanggal_lahir', 'email', 'telp', 'handphone',
        'foto', 'semester_sekarang', 'virtual_account', 'status_aktif',
        'reset_token', 'reset_token_expired',
    ];

    protected $hidden = ['password', 'reset_token'];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'reset_token_expired' => 'datetime',
    ];

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function dosenPA()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pa_id');
    }

    public function tokens()
    {
        return $this->hasMany(TokenMahasiswa::class, 'nim', 'nim');
    }

    public function krs()
    {
        return $this->hasMany(Krs::class, 'nim', 'nim');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'nim', 'nim');
    }

    public function khs()
    {
        return $this->hasMany(Khs::class, 'nim', 'nim');
    }

    public function notifikasi()
    {
        return $this->hasMany(NotifikasiMahasiswa::class, 'nim', 'nim')
                    ->orderBy('created_at', 'desc');
    }

    public function notifikasiUnread()
    {
        return $this->hasMany(NotifikasiMahasiswa::class, 'nim', 'nim')
                    ->where('is_read', 0);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'nim', 'nim');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'nim', 'nim');
    }

    public function jadwalKuliah()
    {
        return $this->hasManyThrough(
            JadwalKuliah::class, Krs::class,
            'nim', 'kode_mk', 'nim', 'kode_mk'
        );
    }

    public function suratPengaduan()
    {
        return $this->hasMany(SuratPengaduan::class, 'nim', 'nim');
    }

    public function bimbinganSkripsi()
    {
        return $this->hasMany(BimbinganSkripsi::class, 'nim', 'nim');
    }

    public function skripsiPengajuan()
    {
        return $this->hasMany(SkripsiPengajuan::class, 'nim', 'nim');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'nim', 'nim');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'nim', 'nim');
    }

    public function pointBook()
    {
        return $this->hasMany(PointBook::class, 'nim', 'nim');
    }

    public function getIpkTerakhirAttribute()
    {
        return $this->khs()->max('ipk') ?? 0;
    }

    public function getTotalSksAttribute()
    {
        return $this->khs()->max('total_sks_kumulatif') ?? 0;
    }
}
// append below - run separately
