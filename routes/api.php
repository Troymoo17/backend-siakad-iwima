<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PushController;
use App\Http\Controllers\Api\MahasiswaController;

// ✅ OPTIONS preflight handler — harus di LUAR prefix group dan PALING ATAS
Route::options('/{any}', function () {
    return response('', 204);
})->where('any', '.*');

Route::prefix('v1')->group(function () {

    // ===== PUBLIC (tanpa auth) =====
    Route::post('/auth/login',   [AuthController::class, 'login']);
    Route::get('/push/vapid-key', [PushController::class, 'vapidKey']);

    // Lupa Password (3 langkah)
    Route::post('/auth/forgot-password',  [AuthController::class, 'forgotPassword']);
    Route::post('/auth/verify-code',      [AuthController::class, 'verifyCode']);
    Route::post('/auth/reset-password',   [AuthController::class, 'resetPassword']);

    // ===== PROTECTED (butuh Bearer Token) =====
    Route::middleware('auth.mahasiswa')->group(function () {

        // Auth
        Route::post('/auth/logout',  [AuthController::class, 'logout']);
        Route::get('/auth/me',       [AuthController::class, 'me']);
        Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

        // ===== PUSH NOTIFICATION =====
        Route::post('/push/subscribe',   [PushController::class, 'subscribe']);
        Route::post('/push/unsubscribe', [PushController::class, 'unsubscribe']);
        Route::post('/push/test',        [PushController::class, 'sendTest']);

        // Dashboard
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard']);

        // Profil
        Route::get('/profil',   [MahasiswaController::class, 'profil']);
        Route::put('/profil',   [MahasiswaController::class, 'updateProfil']);
        Route::post('/profil/foto', [MahasiswaController::class, 'uploadFoto']);

        // Akademik
        Route::get('/krs',            [MahasiswaController::class, 'krs']);
        Route::get('/kurikulum',      [MahasiswaController::class, 'kurikulum']);
        Route::get('/nilai',          [MahasiswaController::class, 'nilai']);
        Route::get('/khs',            [MahasiswaController::class, 'khs']);
        Route::get('/jadwal-kuliah',  [MahasiswaController::class, 'jadwalKuliah']);
        Route::get('/jadwal-ujian',   [MahasiswaController::class, 'jadwalUjian']);
        Route::get('/kehadiran',      [MahasiswaController::class, 'kehadiran']);
        Route::get('/kmk',            [MahasiswaController::class, 'kmk']);

        // Keuangan
        Route::get('/tagihan', [MahasiswaController::class, 'tagihan']);

        // Informasi
        Route::get('/pengumuman',      [MahasiswaController::class, 'pengumuman']);
        Route::get('/pengumuman/{id}', [MahasiswaController::class, 'pengumumanDetail']);
        Route::get('/kalender',        [MahasiswaController::class, 'kalender']);
        Route::get('/banner-kegiatan', [MahasiswaController::class, 'bannerKegiatan']);

        // Notifikasi — urutan penting: /count dan /read-all harus SEBELUM /{id}/read
        Route::get('/notifikasi',            [MahasiswaController::class, 'notifikasi']);
        Route::get('/notifikasi/count',      [MahasiswaController::class, 'notifikasiCount']);
        Route::post('/notifikasi/read-all',  [MahasiswaController::class, 'notifikasiReadAll']);
        Route::post('/notifikasi/{id}/read', [MahasiswaController::class, 'notifikasiRead']);

        // Pengaduan
        Route::get('/pengaduan',  [MahasiswaController::class, 'pengaduan']);
        Route::post('/pengaduan', [MahasiswaController::class, 'storePengaduan']);

        // Bimbingan & Skripsi
        Route::get('/bimbingan-skripsi',   [MahasiswaController::class, 'bimbinganSkripsi']);
        Route::post('/bimbingan-skripsi',  [MahasiswaController::class, 'storeBimbinganSkripsi']);
        Route::get('/bimbingan-proposal',  [MahasiswaController::class, 'bimbinganProposal']);
        Route::get('/bimbingan-sidang',    [MahasiswaController::class, 'bimbinganSidang']);
        Route::get('/skripsi-pengajuan',   [MahasiswaController::class, 'skripsiPengajuan']);
        Route::post('/skripsi-pengajuan',  [MahasiswaController::class, 'storeSkripsiPengajuan']);
        Route::get('/skripsi-ujian',       [MahasiswaController::class, 'skripsiUjian']);
        Route::post('/skripsi-ujian',      [MahasiswaController::class, 'storeSkripsiUjian']);

        // Magang
        Route::get('/magang',  [MahasiswaController::class, 'magang']);
        Route::post('/magang', [MahasiswaController::class, 'storeMagang']);

        // Perpustakaan & Point
        Route::get('/pinjaman',        [MahasiswaController::class, 'pinjaman']);
        Route::get('/point-book',      [MahasiswaController::class, 'pointBook']);
        Route::get('/download-materi',  [MahasiswaController::class, 'downloadMateri']);
        Route::post('/hotspot',          [MahasiswaController::class, 'hotspot']);

        // IKAD & IKAS (kuesioner kepuasan)
        Route::get('/ikad',              [MahasiswaController::class, 'ikad']);
        Route::get('/ikas',              [MahasiswaController::class, 'ikas']);
        Route::post('/kuesioner/submit', [MahasiswaController::class, 'submitKuesioner']);
    });
});