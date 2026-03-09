# Setup Backend SIAKAD IWP

## 1. Install dependencies
```bash
composer install
```

## 2. Generate APP_KEY (WAJIB — key masih kosong di .env)
```bash
php artisan key:generate
```

## 3. Setup database
```bash
# Import SQL ke MySQL
mysql -u root -p siakad_iwp < siakad_iwp_fixed.sql

# Atau buat database dulu:
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS siakad_iwp;"
mysql -u root -p siakad_iwp < siakad_iwp_fixed.sql
```

## 4. Storage link (untuk foto profil & kalender)
```bash
php artisan storage:link
```

## 5. Clear cache
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## 6. Jalankan server
```bash
php artisan serve
# Server berjalan di http://localhost:8000
```

## Checklist perbaikan yang sudah dilakukan:

### Backend
- ✅ bootstrap/app.php — hapus EnsureFrontendRequestsAreStateful (penyebab 419)
- ✅ bootstrap/app.php — prepend CorsMiddleware ke api group
- ✅ CorsMiddleware.php — handle OPTIONS preflight SEBELUM auth middleware
- ✅ config/cors.php — supports_credentials: false (pakai Bearer token, bukan cookie)
- ✅ routes/api.php — OPTIONS handler di atas prefix group
- ✅ routes/api.php — urutan notifikasi: /count & /read-all sebelum /{id}/read
- ✅ MahasiswaAuthenticate.php — inject via attributes (konsisten)
- ✅ MahasiswaController.php — pakai attributes->get() konsisten
- ✅ MahasiswaController.php — tambah foto_profil_url di response profil
- ✅ MahasiswaController.php — fix format response KHS (ips_per_semester)
- ✅ MahasiswaController.php — fix format response tagihan (grouped by semester)
- ✅ MahasiswaController.php — fix pengumuman return items() bukan paginator object
- ✅ .env — FRONTEND_URL=http://localhost:5173

### Frontend
- ✅ api.jsx — fix getNotifikasiCount key: unread_count (bukan count)
- ✅ api.jsx — fix getAnnouncements handle array/paginated response
- ✅ api.jsx — fix getIpkIpsData return data.data (bukan data)
- ✅ api.jsx — auto-logout on 401
- ✅ api.jsx — semua _nim parameter tidak dipakai (token-based auth)
