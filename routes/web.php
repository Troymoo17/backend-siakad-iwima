<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\KurikulumController;
use App\Http\Controllers\Admin\KrsAdminController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\UjianController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\NilaiController;
use App\Http\Controllers\Admin\KeuanganController;
use App\Http\Controllers\Admin\SkripsiController;
use App\Http\Controllers\Admin\MagangController;
use App\Http\Controllers\Admin\PerpustakaanController;
use App\Http\Controllers\Admin\PointBookController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\KalenderController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\PengaduanController;
use App\Http\Controllers\Admin\BannerController;

Route::get('/', fn() => redirect()->route('admin.dashboard'));

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth.admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ── Data Master ──
        Route::resource('/mahasiswa', MahasiswaController::class);
        // Upload foto mahasiswa (via admin panel)
        Route::post('/mahasiswa/{nim}/upload-foto', [MahasiswaController::class, 'uploadFoto'])->name('mahasiswa.upload-foto');
        // KRS Setting (buka/tutup KRS per mahasiswa)
        Route::get('/krs-setting',              [MahasiswaController::class, 'krsSettingIndex'])->name('krs-setting.index');
        Route::post('/krs-setting',             [MahasiswaController::class, 'krsSettingStore'])->name('krs-setting.store');
        Route::patch('/krs-setting/{id}/toggle',[MahasiswaController::class, 'krsSettingToggle'])->name('krs-setting.toggle');
        Route::delete('/krs-setting/{id}',      [MahasiswaController::class, 'krsSettingDestroy'])->name('krs-setting.destroy');
        Route::get('/krs-setting/search-mhs',   [MahasiswaController::class, 'searchMahasiswaKrs'])->name('krs-setting.search-mhs');

        Route::resource('/dosen', DosenController::class);

        // Mata Kuliah + assign dosen
        Route::resource('/matakuliah', MataKuliahController::class);
        Route::post('/matakuliah/{kode}/assign-dosen', [MataKuliahController::class,'assignDosen'])->name('matakuliah.assign-dosen');
        Route::delete('/matakuliah/dosen/{id}', [MataKuliahController::class,'removeDosen'])->name('matakuliah.remove-dosen');

        // Kurikulum
        Route::get('/kurikulum',          [KurikulumController::class,'index'])->name('kurikulum.index');
        Route::post('/kurikulum',         [KurikulumController::class,'store'])->name('kurikulum.store');
        Route::get('/kurikulum/{id}/edit',[KurikulumController::class,'edit'])->name('kurikulum.edit');
        Route::put('/kurikulum/{id}',     [KurikulumController::class,'update'])->name('kurikulum.update');
        Route::delete('/kurikulum/{id}',  [KurikulumController::class,'destroy'])->name('kurikulum.destroy');

        // ── Perkuliahan ──
        // KRS
        Route::get('/krs',                   [KrsAdminController::class,'index'])->name('krs.index');
        Route::post('/krs',                  [KrsAdminController::class,'store'])->name('krs.store');
        Route::patch('/krs/{id}/approve',    [KrsAdminController::class,'approve'])->name('krs.approve');
        Route::post('/krs/approve-all',      [KrsAdminController::class,'approveAll'])->name('krs.approve-all');
        Route::delete('/krs/{id}',           [KrsAdminController::class,'destroy'])->name('krs.destroy');
        Route::get('/krs/search-mhs',        [KrsAdminController::class,'searchMahasiswa'])->name('krs.search-mhs');

        // Jadwal Kuliah
        Route::get('/jadwal',            [JadwalController::class,'index'])->name('jadwal.index');
        Route::post('/jadwal',           [JadwalController::class,'store'])->name('jadwal.store');
        Route::get('/jadwal/{id}/edit',  [JadwalController::class,'edit'])->name('jadwal.edit');
        Route::put('/jadwal/{id}',       [JadwalController::class,'update'])->name('jadwal.update');
        Route::delete('/jadwal/{id}',    [JadwalController::class,'destroy'])->name('jadwal.destroy');

        // Jadwal Ujian
        Route::get('/ujian',            [UjianController::class,'index'])->name('ujian.index');
        Route::post('/ujian',           [UjianController::class,'store'])->name('ujian.store');
        Route::get('/ujian/{id}/edit',  [UjianController::class,'edit'])->name('ujian.edit');
        Route::put('/ujian/{id}',       [UjianController::class,'update'])->name('ujian.update');
        Route::delete('/ujian/{id}',    [UjianController::class,'destroy'])->name('ujian.destroy');

        // Kehadiran
        Route::get('/kehadiran',              [KehadiranController::class,'index'])->name('kehadiran.index');
        Route::post('/kehadiran',             [KehadiranController::class,'store'])->name('kehadiran.store');
        Route::post('/kehadiran/bulk',        [KehadiranController::class,'bulkStore'])->name('kehadiran.bulk-store');
        Route::get('/kehadiran/bulk-form',    [KehadiranController::class,'bulkForm'])->name('kehadiran.bulk-form');
        Route::delete('/kehadiran/{id}',      [KehadiranController::class,'destroy'])->name('kehadiran.destroy');
        Route::get('/kehadiran/search-mhs',   [KehadiranController::class,'searchMahasiswa'])->name('kehadiran.search-mhs');

        // Nilai
        Route::get('/nilai',  [NilaiController::class,'index'])->name('nilai.index');
        Route::post('/nilai', [NilaiController::class,'store'])->name('nilai.store');

        // ── Keuangan ──
        Route::get('/keuangan',                      [KeuanganController::class,'index'])->name('keuangan.index');
        Route::post('/keuangan/tagihan',             [KeuanganController::class,'storeTagihan'])->name('keuangan.tagihan.store');
        Route::post('/keuangan/tagihan/bulk',        [KeuanganController::class,'generateTagihanBulk'])->name('keuangan.tagihan.bulk');
        Route::delete('/keuangan/tagihan/{id}',      [KeuanganController::class,'destroyTagihan'])->name('keuangan.tagihan.destroy');
        Route::post('/keuangan/pembayaran',          [KeuanganController::class,'storePembayaran'])->name('keuangan.pembayaran.store');
        Route::get('/keuangan/search-mhs',           [KeuanganController::class,'searchMahasiswa'])->name('keuangan.search-mhs');

        // ── Layanan ──
        // Skripsi
        Route::get('/skripsi',                       [SkripsiController::class,'index'])->name('skripsi.index');
        Route::patch('/skripsi/{id}/status',         [SkripsiController::class,'updateStatus'])->name('skripsi.update-status');
        Route::patch('/skripsi/bimbingan/{id}',      [SkripsiController::class,'updateBimbingan'])->name('skripsi.bimbingan-update');
        Route::get('/skripsi/search-mhs',            [SkripsiController::class,'searchMahasiswa'])->name('skripsi.search-mhs');

        // Magang
        Route::get('/magang',                        [MagangController::class,'index'])->name('magang.index');
        Route::patch('/magang/{id}/status',          [MagangController::class,'updateStatus'])->name('magang.update-status');
        Route::get('/magang/search-mhs',             [MagangController::class,'searchMahasiswa'])->name('magang.search-mhs');

        // Perpustakaan
        Route::get('/perpustakaan',                   [PerpustakaanController::class,'index'])->name('perpustakaan.index');
        Route::post('/perpustakaan',                  [PerpustakaanController::class,'store'])->name('perpustakaan.store');
        Route::patch('/perpustakaan/{id}/kembalikan', [PerpustakaanController::class,'kembalikan'])->name('perpustakaan.kembalikan');
        Route::delete('/perpustakaan/{id}',           [PerpustakaanController::class,'destroy'])->name('perpustakaan.destroy');
        Route::get('/perpustakaan/search-mhs',        [PerpustakaanController::class,'searchMahasiswa'])->name('perpustakaan.search-mhs');

        // Point Book
        Route::get('/pointbook',            [PointBookController::class,'index'])->name('pointbook.index');
        Route::post('/pointbook',           [PointBookController::class,'store'])->name('pointbook.store');
        Route::delete('/pointbook/{id}',    [PointBookController::class,'destroy'])->name('pointbook.destroy');
        Route::get('/pointbook/search-mhs', [PointBookController::class,'searchMahasiswa'])->name('pointbook.search-mhs');

        // ── Informasi ──
        Route::resource('/pengumuman', PengumumanController::class);

        // Kalender + multi-gambar kegiatan
        Route::resource('/kalender', KalenderController::class);
        Route::get('/banner',              [BannerController::class, 'index'])->name('banner.index');
        Route::post('/banner',             [BannerController::class, 'store'])->name('banner.store');
        Route::patch('/banner/{id}/toggle',[BannerController::class, 'toggleAktif'])->name('banner.toggle');
        Route::post('/banner/urutan',      [BannerController::class, 'updateUrutan'])->name('banner.urutan');
        Route::delete('/banner/{id}',      [BannerController::class, 'destroy'])->name('banner.destroy');
        Route::delete('/kalender/gambar/{id}', [KalenderController::class,'destroyGambar'])->name('kalender.gambar.destroy');

        Route::get('/download',     [DownloadController::class,'index'])->name('download.index');
        Route::post('/download',    [DownloadController::class,'store'])->name('download.store');
        Route::delete('/download/{id}', [DownloadController::class,'destroy'])->name('download.destroy');

        Route::resource('/notifikasi', NotifikasiController::class)->except(['show','edit','update']);
        Route::get('/pengaduan',         [PengaduanController::class,'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{id}',    [PengaduanController::class,'show'])->name('pengaduan.show');
        Route::post('/pengaduan/{id}/balas', [PengaduanController::class,'balas'])->name('pengaduan.balas');
    });
});