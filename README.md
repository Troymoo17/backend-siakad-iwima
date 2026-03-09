# SIAKAD IWP - Backend Laravel 12

Sistem Informasi Akademik Institut Widya Pratama Pekalongan

## 📋 Fitur Lengkap

### 🖥️ Admin Panel (Blade)
- **Dashboard** - Statistik mahasiswa, dosen, pengaduan, tagihan
- **Manajemen Mahasiswa** - CRUD mahasiswa, filter, detail lengkap
- **Input Nilai** - Input nilai + otomatis update KHS + kirim notifikasi
- **Pengumuman** - Buat pengumuman, otomatis broadcast notifikasi ke mahasiswa
- **Notifikasi** - Kirim notifikasi ke: semua/prodi/kelas/personal
- **Pengaduan** - Kelola & balas surat pengaduan mahasiswa

### 🔌 REST API (untuk Frontend)
- **Auth** - Login/Logout dengan custom Bearer Token
- **Dashboard** - Summary akademik, keuangan, notifikasi
- **Profil** - Data diri mahasiswa
- **KRS** - Kartu Rencana Studi
- **Nilai** - Nilai per semester
- **KHS** - Kartu Hasil Studi + IPK/IPS
- **Jadwal Kuliah & Ujian**
- **Kehadiran** - Rekap per mata kuliah
- **Tagihan & Keuangan**
- **Pengumuman & Kalender Akademik**
- **Notifikasi** - Real-time count, mark as read, mark all read
- **Pengaduan** - Kirim & lihat status
- **Skripsi** - Bimbingan & pengajuan
- **Magang** - Pengajuan & status
- **Perpustakaan** - Daftar pinjaman
- **Point Book** - Riwayat poin kegiatan

---

## 🚀 Cara Setup

### 1. Clone / Extract Project
```bash
# Masuk ke direktori project
cd siakad-backend
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai konfigurasi lokal Anda:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_anda
DB_USERNAME=root
DB_PASSWORD=

# URL frontend Anda
FRONTEND_URL=URL_FRONTREND
```

### 4. Setup Storage
```bash
php artisan storage:link
```

### 5. Jalankan Server
```bash
php artisan serve
# Server berjalan di http://localhost:8000
```

---

## 📡 API Documentation

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication
Semua endpoint yang terproteksi membutuhkan header:
```
Authorization: Bearer {token}
```

### Endpoints

#### Auth
```
POST   /api/v1/auth/login          Login mahasiswa
POST   /api/v1/auth/logout         Logout
GET    /api/v1/auth/me             Info mahasiswa login
POST   /api/v1/auth/refresh        Refresh token
```

#### Dashboard & Profil
```
GET    /api/v1/dashboard           Summary dashboard
GET    /api/v1/profil              Data diri lengkap
PUT    /api/v1/profil              Update profil
```

#### Akademik
```
GET    /api/v1/krs                 Daftar KRS (?semester=6&tahun_akademik=2025/2026)
GET    /api/v1/nilai               Nilai (?semester=6)
GET    /api/v1/khs                 KHS + IPK kumulatif
GET    /api/v1/jadwal-kuliah       Jadwal kuliah semester ini
GET    /api/v1/jadwal-ujian        Jadwal ujian
GET    /api/v1/kehadiran           Kehadiran (?kode_mk=INF601)
```

#### Keuangan
```
GET    /api/v1/tagihan             Daftar tagihan + status bayar
```

#### Informasi
```
GET    /api/v1/pengumuman          Daftar pengumuman (paginated)
GET    /api/v1/pengumuman/{id}     Detail pengumuman
GET    /api/v1/kalender            Kalender akademik
```

#### Notifikasi ⭐
```
GET    /api/v1/notifikasi          Daftar notifikasi (paginated)
GET    /api/v1/notifikasi/count    Jumlah notifikasi belum dibaca
POST   /api/v1/notifikasi/{id}/read   Tandai 1 notifikasi dibaca
POST   /api/v1/notifikasi/read-all    Tandai semua dibaca
```

#### Pengaduan
```
GET    /api/v1/pengaduan           Daftar surat pengaduan
POST   /api/v1/pengaduan           Kirim surat pengaduan
```

#### Skripsi
```
GET    /api/v1/bimbingan-skripsi   Riwayat bimbingan
POST   /api/v1/bimbingan-skripsi   Ajukan bimbingan baru
GET    /api/v1/skripsi-pengajuan   Status pengajuan skripsi
POST   /api/v1/skripsi-pengajuan   Ajukan skripsi
```

#### Magang
```
GET    /api/v1/magang              Riwayat pengajuan magang
POST   /api/v1/magang              Ajukan magang baru
```

#### Perpustakaan & Lainnya
```
GET    /api/v1/pinjaman            Daftar pinjaman buku
GET    /api/v1/point-book          Riwayat point book
```


## 🏗️ Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          ← Controller Blade (Admin Panel)
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── MahasiswaController.php
│   │   │   ├── NilaiController.php
│   │   │   ├── NotifikasiController.php
│   │   │   ├── PengaduanController.php
│   │   │   └── PengumumanController.php
│   │   └── Api/            ← Controller API
│   │       ├── AuthController.php
│   │       └── MahasiswaController.php
│   └── Middleware/
│       ├── AdminAuthenticate.php
│       ├── CorsMiddleware.php
│       └── MahasiswaAuthenticate.php
├── Models/                 ← Semua Model Eloquent
├── Services/
│   └── NotificationService.php  ← Logic notifikasi terpusat
└── Providers/
    └── AppServiceProvider.php

resources/views/admin/     ← Blade Views
├── layouts/app.blade.php
├── auth/login.blade.php
├── dashboard.blade.php
├── mahasiswa/
├── nilai/
├── notifikasi/
├── pengumuman/
└── pengaduan/

routes/
├── api.php               ← API Routes
└── web.php               ← Admin Web Routes
```


