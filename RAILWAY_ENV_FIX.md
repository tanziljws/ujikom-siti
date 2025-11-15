# Fix Environment Variables untuk Railway

## Masalah yang Ditemukan

Environment variables Anda sudah hampir benar, tapi ada beberapa yang perlu diubah:

1. ❌ `SESSION_DRIVER="database"` → Harus `"file"` (untuk menghindari error saat boot)
2. ❌ `CACHE_STORE="database"` → Sebaiknya `"file"` (untuk menghindari error saat boot)
3. ⚠️ `LOG_CHANNEL="stack"` → Sebaiknya `"stderr"` (agar logs muncul di Railway dashboard)
4. ⚠️ `SESSION_ENCRYPT="true"` → Bisa `"false"` jika pakai file driver

## Environment Variables yang Benar

Copy-paste ini ke Railway Dashboard → Variables (replace yang lama):

```env
APP_NAME="Galeri SMKN 4 Bogor"
APP_ENV="production"
APP_KEY="base64:29El65+fbTbMJZnMYb2fw3pYbpB+xOFb/7m9a5SeNgg="
APP_DEBUG="false"
APP_URL="https://ujikom-siti-production.up.railway.app"
APP_LOCALE="en"
APP_FALLBACK_LOCALE="en"
APP_FAKER_LOCALE="en_US"
APP_MAINTENANCE_DRIVER="file"
APP_MAINTENANCE_STORE="database"
PHP_CLI_SERVER_WORKERS="4"
BCRYPT_ROUNDS="12"
SANCTUM_STATEFUL_DOMAINS="ujikom-siti-production.up.railway.app"

# Logging - PAKAI stderr untuk Railway
LOG_CHANNEL="stderr"
LOG_STACK="daily"
LOG_DEPRECATIONS_CHANNEL="null"
LOG_LEVEL="error"

# Database Configuration - SUDAH BENAR ✅
DB_CONNECTION="mysql"
DB_HOST="crossover.proxy.rlwy.net"
DB_PORT="14902"
DB_DATABASE="railway"
DB_USERNAME="root"
DB_PASSWORD="jbhERRMobIkaLTxpPFZZVPAxpwRolhpd"

# Session - UBAH KE FILE ⚠️
SESSION_DRIVER="file"
SESSION_LIFETIME="120"
SESSION_ENCRYPT="false"
SESSION_PATH="/"
SESSION_DOMAIN="null"
SESSION_SECURE_COOKIE="true"
SESSION_SAME_SITE="lax"

BROADCAST_CONNECTION="log"
FILESYSTEM_DISK="local"
QUEUE_CONNECTION="sync"

# Cache - UBAH KE FILE ⚠️
CACHE_STORE="file"
CACHE_PREFIX="galeri_cache"

VITE_APP_NAME="${APP_NAME}"
```

## Perubahan yang Perlu Dilakukan

### 1. Ubah SESSION_DRIVER
- **Dari:** `SESSION_DRIVER="database"`
- **Ke:** `SESSION_DRIVER="file"`

**Alasan:** Saat aplikasi boot, jika session driver pakai database, Laravel akan langsung coba connect ke database. Jika database belum ready atau ada masalah connection, akan error 500. Dengan `file`, aplikasi bisa boot tanpa perlu database connection dulu.

### 2. Ubah CACHE_STORE
- **Dari:** `CACHE_STORE="database"`
- **Ke:** `CACHE_STORE="file"`

**Alasan:** Sama seperti session, cache yang pakai database juga akan coba connect saat boot.

### 3. Ubah LOG_CHANNEL (Optional tapi Recommended)
- **Dari:** `LOG_CHANNEL="stack"`
- **Ke:** `LOG_CHANNEL="stderr"`

**Alasan:** Railway akan menampilkan logs dari stderr di dashboard, lebih mudah untuk debugging.

### 4. Ubah SESSION_ENCRYPT
- **Dari:** `SESSION_ENCRYPT="true"`
- **Ke:** `SESSION_ENCRYPT="false"`

**Alasan:** Jika pakai file driver, encryption tidak terlalu penting dan bisa mengurangi overhead.

## Langkah-langkah

1. **Buka Railway Dashboard**
   - Login ke https://railway.app
   - Pilih project Anda
   - Klik tab "Variables"

2. **Edit Variables**
   - Cari `SESSION_DRIVER` → Ubah dari `"database"` ke `"file"`
   - Cari `CACHE_STORE` → Ubah dari `"database"` ke `"file"`
   - Cari `LOG_CHANNEL` → Ubah dari `"stack"` ke `"stderr"` (optional)
   - Cari `SESSION_ENCRYPT` → Ubah dari `"true"` ke `"false"` (optional)

3. **Save dan Redeploy**
   - Klik "Save" atau "Update"
   - Railway akan otomatis redeploy
   - Atau klik "Redeploy" manual

4. **Jalankan Migration (jika belum)**
   - Setelah deploy, buka Railway Console
   - Jalankan: `php artisan migrate --force`

## Setelah Fix

Setelah environment variables diubah dan aplikasi di-redeploy:
1. ✅ Aplikasi bisa boot tanpa error 500
2. ✅ Database connection akan bekerja untuk query aplikasi
3. ✅ Session dan cache akan disimpan di file (lebih reliable)
4. ✅ Logs akan muncul di Railway dashboard

## Catatan

- **Session & Cache di File:** Data session dan cache akan disimpan di `storage/framework/sessions` dan `storage/framework/cache`. Ini persistent di Railway.
- **Database untuk Data:** Database tetap digunakan untuk menyimpan data aplikasi (galeri, kategori, dll).
- **Bisa Kembali ke Database:** Setelah aplikasi stabil dan database sudah pasti ready, bisa kembali pakai `database` untuk session/cache jika mau.

