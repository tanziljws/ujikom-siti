# Railway Deployment Guide

## Setup Environment Variables di Railway

Pastikan set environment variables berikut di Railway dashboard:

### Required Variables

```env
APP_NAME="Galeri Edu"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.railway.app

# Database (Railway akan auto-generate ini jika pakai Railway PostgreSQL/MySQL)
DB_CONNECTION=pgsql  # atau mysql
DB_HOST=${{Postgres.PGHOST}}  # atau ${{MySQL.MYSQLHOST}}
DB_PORT=${{Postgres.PGPORT}}  # atau ${{MySQL.MYSQLPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}  # atau ${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}  # atau ${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}  # atau ${{MySQL.MYSQLPASSWORD}}

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### Generate APP_KEY

Jika belum ada APP_KEY, jalankan di local:
```bash
php artisan key:generate --show
```

Copy hasilnya dan set di Railway sebagai `APP_KEY`.

## Setup Database

1. **Tambahkan Database Service di Railway:**
   - Klik "New" → "Database" → Pilih PostgreSQL atau MySQL
   - Railway akan auto-generate connection variables

2. **Jalankan Migrations:**
   - Setelah deploy pertama, buka Railway console
   - Jalankan: `php artisan migrate --force`
   - Atau gunakan Railway's release command (akan otomatis jalan)

3. **Seed Database (Optional):**
   ```bash
   php artisan db:seed
   ```

## Setup Storage

Aplikasi menggunakan `public/uploads/galeri` untuk upload file. Folder ini akan otomatis dibuat saat aplikasi pertama kali upload.

Untuk storage link (jika diperlukan):
```bash
php artisan storage:link
```

## Troubleshooting

### Error 500 Internal Server Error

1. **Cek Logs di Railway:**
   - Buka Railway dashboard → Deployments → View Logs
   - Cari error message

2. **Common Issues:**

   **a. APP_KEY tidak ada:**
   ```bash
   php artisan key:generate --force
   ```

   **b. Database connection error:**
   - Pastikan database service sudah running
   - Cek environment variables DB_* sudah benar
   - Pastikan database sudah dibuat

   **c. Permission error:**
   - Railway biasanya handle permissions otomatis
   - Jika masih error, pastikan storage folder ada:
     ```bash
     mkdir -p storage/framework/sessions
     mkdir -p storage/framework/views
     mkdir -p storage/framework/cache
     mkdir -p storage/logs
     mkdir -p public/uploads/galeri
     ```

   **d. Session error:**
   - Pastikan `SESSION_DRIVER=file` di environment variables
   - Pastikan folder `storage/framework/sessions` ada dan writable

3. **Clear Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Rebuild:**
   - Di Railway dashboard, klik "Redeploy"
   - Atau push commit baru ke repository

## Railway Release Command (Optional)

Jika ingin setup otomatis saat deploy, tambahkan di Railway:

**Release Command:**
```bash
php artisan migrate --force && php artisan storage:link || true
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=$PORT
```

## Verifikasi Deployment

1. Buka URL aplikasi di browser
2. Cek apakah halaman utama load
3. Coba login dengan credentials default:
   - Admin: `admin@gmail.com` / `admin123`
   - Staff: `siti@gmail.com` / `staff123`

## Notes

- Railway menggunakan port dari environment variable `$PORT`
- File uploads disimpan di `public/uploads/galeri` (persistent)
- Session disimpan di `storage/framework/sessions` (persistent)
- Logs bisa dilihat di Railway dashboard atau `storage/logs/laravel.log`

