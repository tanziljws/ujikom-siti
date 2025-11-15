# Troubleshooting Database Connection Error di Railway

## Langkah Debugging

### 1. Test Database Connection

Setelah deploy, akses URL ini untuk test connection:
```
https://ujikom-siti-production.up.railway.app/test-db
```

Route ini akan menampilkan:
- Status connection
- Error message (jika ada)
- Environment variables yang ter-load
- Config yang digunakan

### 2. Cek Environment Variables di Railway

Pastikan semua variable ini sudah di-set dengan benar:

```env
DB_CONNECTION=mysql
DB_HOST=crossover.proxy.rlwy.net
DB_PORT=14902
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=jbhERRMobIkaLTxpPFZZVPAxpwRolhpd
```

**PENTING:**
- Pastikan tidak ada spasi di awal/akhir value
- Pastikan password benar (case-sensitive)
- Pastikan port adalah string/number yang benar

### 3. Cek Railway Database Service

1. Buka Railway Dashboard
2. Pastikan MySQL service sudah **Running**
3. Cek apakah database service sudah ter-connect ke aplikasi
4. Cek **Variables** tab di database service untuk memastikan credentials benar

### 4. Test Connection dari Railway Console

1. Buka Railway Dashboard → Your Project
2. Klik tab **Deployments** → Pilih deployment terbaru
3. Klik **View Logs** atau buka **Console**
4. Jalankan command:
   ```bash
   php artisan tinker
   ```
5. Di tinker, coba:
   ```php
   DB::connection()->getPdo();
   ```
6. Jika error, akan muncul error message yang lebih detail

### 5. Cek Logs di Railway

1. Buka Railway Dashboard → Deployments
2. Klik deployment terbaru
3. Klik **View Logs**
4. Cari error message tentang database connection
5. Error biasanya seperti:
   - `SQLSTATE[HY000] [2002] Connection refused`
   - `SQLSTATE[HY000] [1045] Access denied`
   - `SQLSTATE[HY000] [2006] MySQL server has gone away`

## Common Issues & Solutions

### Issue 1: Connection Refused

**Error:** `SQLSTATE[HY000] [2002] Connection refused`

**Penyebab:**
- Database service belum running
- Host atau port salah
- Firewall blocking connection

**Solusi:**
1. Pastikan database service sudah running di Railway
2. Cek host dan port di environment variables
3. Pastikan aplikasi dan database service dalam project yang sama

### Issue 2: Access Denied

**Error:** `SQLSTATE[HY000] [1045] Access denied for user`

**Penyebab:**
- Username atau password salah
- User tidak punya permission

**Solusi:**
1. Cek username dan password di Railway database service variables
2. Pastikan password tidak ada spasi di awal/akhir
3. Coba reset password database di Railway

### Issue 3: Unknown Database

**Error:** `SQLSTATE[HY000] [1049] Unknown database`

**Penyebab:**
- Database name salah
- Database belum dibuat

**Solusi:**
1. Cek database name di environment variables
2. Pastikan database sudah dibuat di Railway
3. Coba buat database baru jika perlu

### Issue 4: SSL Connection Error

**Error:** SSL-related errors

**Penyebab:**
- Railway MySQL mungkin perlu SSL configuration

**Solusi:**
Sudah ditambahkan di `config/database.php`:
```php
PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
```

## Quick Fix Checklist

- [ ] Environment variables sudah di-set di Railway
- [ ] `DB_CONNECTION=mysql` (bukan sqlite)
- [ ] Database service sudah running
- [ ] Host, port, database, username, password benar
- [ ] Sudah redeploy setelah ubah environment variables
- [ ] Sudah test dengan `/test-db` route
- [ ] Sudah cek logs di Railway dashboard

## Next Steps

1. **Akses `/test-db` route** untuk melihat error detail
2. **Cek logs** di Railway dashboard
3. **Test connection** dari Railway console
4. **Pastikan database service running**
5. **Verify environment variables** sudah benar

Setelah dapat error message dari `/test-db`, kita bisa fix lebih spesifik!

