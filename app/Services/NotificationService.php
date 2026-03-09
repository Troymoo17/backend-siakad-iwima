<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\NotifikasiMahasiswa;
use App\Models\Mahasiswa;
use App\Services\PushNotificationService;

class NotificationService
{
    protected PushNotificationService $push;

    public function __construct(PushNotificationService $push)
    {
        $this->push = $push;
    }

    /**
     * Kirim notifikasi ke semua mahasiswa
     */
    public function sendToAll(string $judul, string $pesan, string $tipe = 'info', ?string $link = null, ?int $createdBy = null): Notifikasi
    {
        $notifikasi = Notifikasi::create([
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'target' => 'all',
            'link' => $link,
            'created_by' => $createdBy,
            'is_active' => 1,
        ]);

        $mahasiswas = Mahasiswa::where('status_aktif', 'Aktif')->get(['nim']);

        $data = $mahasiswas->map(fn($m) => [
            'notifikasi_id' => $notifikasi->id,
            'nim' => $m->nim,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'link' => $link,
            'is_read' => 0,
            'created_at' => now(),
        ])->toArray();

        foreach (array_chunk($data, 100) as $chunk) {
            NotifikasiMahasiswa::insert($chunk);
        }

        // Kirim push notification ke semua mahasiswa aktif
        $this->push->broadcast($judul, $pesan, ['link' => $link]);

        return $notifikasi;
    }

    /**
     * Kirim notifikasi ke satu mahasiswa
     */
    public function sendToMahasiswa(string $nim, string $judul, string $pesan, string $tipe = 'info', ?string $link = null, ?int $notifikasiId = null): NotifikasiMahasiswa
    {
        $notif = NotifikasiMahasiswa::create([
            'notifikasi_id' => $notifikasiId,
            'nim' => $nim,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'link' => $link,
            'is_read' => 0,
        ]);

        // Kirim push notification ke mahasiswa
        $this->push->sendToNim($nim, $judul, $pesan, ['link' => $link]);

        return $notif;
    }

    /**
     * Kirim notifikasi ke prodi tertentu
     */
    public function sendToProdi(string $prodi, string $judul, string $pesan, string $tipe = 'info', ?string $link = null, ?int $createdBy = null): Notifikasi
    {
        $notifikasi = Notifikasi::create([
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'target' => 'prodi',
            'target_value' => $prodi,
            'link' => $link,
            'created_by' => $createdBy,
            'is_active' => 1,
        ]);

        $mahasiswas = Mahasiswa::where('status_aktif', 'Aktif')
            ->where('prodi', $prodi)
            ->get(['nim']);

        $data = $mahasiswas->map(fn($m) => [
            'notifikasi_id' => $notifikasi->id,
            'nim' => $m->nim,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'link' => $link,
            'is_read' => 0,
            'created_at' => now(),
        ])->toArray();

        foreach (array_chunk($data, 100) as $chunk) {
            NotifikasiMahasiswa::insert($chunk);
        }

        // Kirim push notification ke tiap mahasiswa di prodi
        foreach ($mahasiswas as $m) {
            $this->push->sendToNim($m->nim, $judul, $pesan, ['link' => $link]);
        }

        return $notifikasi;
    }

    /**
     * Kirim notifikasi ke kelas tertentu
     */
    public function sendToKelas(string $kelas, string $judul, string $pesan, string $tipe = 'info', ?string $link = null, ?int $createdBy = null): Notifikasi
    {
        $notifikasi = Notifikasi::create([
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'target' => 'kelas',
            'target_value' => $kelas,
            'link' => $link,
            'created_by' => $createdBy,
            'is_active' => 1,
        ]);

        $mahasiswas = Mahasiswa::where('status_aktif', 'Aktif')
            ->where('kelas', $kelas)
            ->get(['nim']);

        $data = $mahasiswas->map(fn($m) => [
            'notifikasi_id' => $notifikasi->id,
            'nim' => $m->nim,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'link' => $link,
            'is_read' => 0,
            'created_at' => now(),
        ])->toArray();

        foreach (array_chunk($data, 100) as $chunk) {
            NotifikasiMahasiswa::insert($chunk);
        }

        // Kirim push notification ke tiap mahasiswa di kelas
        foreach ($mahasiswas as $m) {
            $this->push->sendToNim($m->nim, $judul, $pesan, ['link' => $link]);
        }

        return $notifikasi;
    }

    /**
     * Notifikasi otomatis saat nilai diinput
     */
    public function notifyNilaiInput(string $nim, string $namaMk, int $semester): void
    {
        $this->sendToMahasiswa(
            $nim,
            'Nilai Telah Diinput',
            "Nilai mata kuliah {$namaMk} untuk semester {$semester} telah tersedia. Silakan cek di menu Nilai.",
            'nilai',
            '/mahasiswa/nilai'
        );
    }

    /**
     * Notifikasi otomatis saat KRS disetujui
     */
    public function notifyKrsApproved(string $nim, int $semester, string $tahunAkademik): void
    {
        $this->sendToMahasiswa(
            $nim,
            'KRS Disetujui',
            "KRS semester {$semester} tahun akademik {$tahunAkademik} Anda telah disetujui oleh dosen PA.",
            'krs',
            '/mahasiswa/krs'
        );
    }

    /**
     * Notifikasi tagihan baru
     */
    public function notifyTagihanBaru(string $nim, string $jenisTagihan, float $nominal, int $semester): void
    {
        $nominalFormatted = 'Rp ' . number_format($nominal, 0, ',', '.');
        $this->sendToMahasiswa(
            $nim,
            'Tagihan Baru',
            "Tagihan {$jenisTagihan} semester {$semester} sebesar {$nominalFormatted} telah diterbitkan.",
            'keuangan',
            '/mahasiswa/keuangan'
        );
    }

    /**
     * Notifikasi pengaduan dibalas
     */
    public function notifyPengaduanDibalas(string $nim, string $perihal): void
    {
        $this->sendToMahasiswa(
            $nim,
            'Pengaduan Dibalas',
            "Surat pengaduan Anda dengan perihal \"{$perihal}\" telah mendapat balasan.",
            'info',
            '/mahasiswa/pengaduan'
        );
    }

    /**
     * Notifikasi bimbingan skripsi diupdate
     */
    public function notifyBimbinganUpdate(string $nim, string $bab, string $status): void
    {
        $this->sendToMahasiswa(
            $nim,
            'Update Bimbingan Skripsi',
            "Bimbingan {$bab} Anda telah diupdate dengan status: {$status}.",
            'skripsi',
            '/mahasiswa/skripsi'
        );
    }

    /**
     * Notifikasi pengajuan magang diproses
     */
    public function notifyMagangDiproses(string $nim, string $status, string $tempat): void
    {
        $this->sendToMahasiswa(
            $nim,
            'Status Magang Diperbarui',
            "Pengajuan magang Anda di {$tempat} telah {$status}.",
            'info',
            '/mahasiswa/magang'
        );
    }

    /**
     * Mark notifikasi sebagai dibaca
     */
    public function markAsRead(int $id, string $nim): bool
    {
        return NotifikasiMahasiswa::where('id', $id)
            ->where('nim', $nim)
            ->update(['is_read' => 1, 'read_at' => now()]) > 0;
    }

    /**
     * Mark semua notifikasi mahasiswa sebagai dibaca
     */
    public function markAllAsRead(string $nim): int
    {
        return NotifikasiMahasiswa::where('nim', $nim)
            ->where('is_read', 0)
            ->update(['is_read' => 1, 'read_at' => now()]);
    }

    /**
     * Hitung notifikasi belum dibaca
     */
    public function countUnread(string $nim): int
    {
        return NotifikasiMahasiswa::where('nim', $nim)->where('is_read', 0)->count();
    }
}