<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';
    public $timestamps = false;

    protected $fillable = [
        'nim', 'kode_mk', 'semester', 'tahun_akademik',
        'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir',
        'grade', 'bobot', 'sks', 'bobot_sks', 'dosen_id',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'nilai_tugas' => 'float',
        'nilai_uts' => 'float',
        'nilai_uas' => 'float',
        'nilai_akhir' => 'float',
        'bobot' => 'float',
        'bobot_sks' => 'float',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'kode_mk');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public static function hitungGrade(float $nilai): array
    {
        if ($nilai >= 85) return ['grade' => 'A', 'bobot' => 4.00];
        if ($nilai >= 80) return ['grade' => 'A-', 'bobot' => 3.75];
        if ($nilai >= 75) return ['grade' => 'B+', 'bobot' => 3.50];
        if ($nilai >= 70) return ['grade' => 'B', 'bobot' => 3.00];
        if ($nilai >= 65) return ['grade' => 'B-', 'bobot' => 2.75];
        if ($nilai >= 60) return ['grade' => 'C+', 'bobot' => 2.50];
        if ($nilai >= 55) return ['grade' => 'C', 'bobot' => 2.00];
        if ($nilai >= 40) return ['grade' => 'D', 'bobot' => 1.00];
        return ['grade' => 'E', 'bobot' => 0.00];
    }
}
