# Setup Environment Variables untuk Railway

## Masalah yang Ditemukan

Dari `.env` yang ada, beberapa konfigurasi masih salah:
1. ❌ `DB_CONNECTION=sqlite` - harus `mysql`
2. ❌ Tidak ada `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
3. ❌ `SESSION_DRIVER=database` - harus `file` (untuk menghindari error saat database belum ready)
4. ❌ `CACHE_STORE=database` - sebaiknya `file`

## Environment Variables yang Harus Di-Set di Railway

Buka Railway Dashboard → Your Project → Variables, lalu set:

```env
# App Configuration
APP_NAME="Galeri SMKN 4 Bogor"
APP_ENV=production
APP_KEY=base64:29El65+fbTbMJZnMYb2fw3pYbpB+xOFb/7m9a5SeNgg=
APP_DEBUG=false
APP_URL=https://ujikom-siti-production.up.railway.app

# Database Configuration - PENTING!
DB_CONNECTION=mysql
DB_HOST=crossover.proxy.rlwy.net
DB_PORT=14902
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=jbhERRMobIkaLTxpPFZZVPAxpwRolhpd

# Session - PAKAI FILE (penting untuk menghindari error)
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Cache - PAKAI FILE
CACHE_STORE=file

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=error

# Other
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
```

## Langkah-langkah

1. **Buka Railway Dashboard**
   - Login ke https://railway.app
   - Pilih project Anda

2. **Set Environment Variables**
   - Klik tab "Variables"
   - Tambahkan/Edit setiap variable di atas
   - **PENTING**: Pastikan `DB_CONNECTION=mysql` (bukan sqlite!)
   - **PENTING**: Pastikan `SESSION_DRIVER=file` (bukan database!)

3. **Redeploy**
   - Setelah set semua variables, klik "Redeploy" atau push commit baru
   - Tunggu deployment selesai

4. **Jalankan Migration**
   - Setelah deploy, buka Railway Console
   - Jalankan: `php artisan migrate --force`

## Verifikasi

Setelah deploy, cek:
1. Aplikasi bisa diakses tanpa error 500
2. Database connection berhasil
3. Bisa login ke dashboard

## Troubleshooting

Jika masih error:
1. Cek logs di Railway Dashboard → Deployments → View Logs
2. Pastikan semua environment variables sudah di-set dengan benar
3. Pastikan database service sudah running di Railway
4. Cek apakah password database benar (tanpa spasi di awal/akhir)

