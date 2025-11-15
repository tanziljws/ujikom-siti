# Debug Error 500 - Langkah-langkah

## 1. Cek Logs di Railway

Buka Railway Dashboard → Deployments → View Logs, cari error message terbaru.

## 2. Test Route Debug

Akses route ini untuk melihat error detail:
```
https://ujikom-siti-production.up.railway.app/test-homepage-debug
```

## 3. Cek Apakah View Error

Coba akses route ini untuk test view:
```
https://ujikom-siti-production.up.railway.app/test-homepage
```

## 4. Kemungkinan Masalah

Berdasarkan fitur yang ditambahkan teman Anda, kemungkinan masalahnya:

1. **SiteSetting query error** - View menggunakan `SiteSetting::get()` yang mungkin error jika tabel kosong
2. **View syntax error** - Mungkin ada syntax error di view
3. **Missing variable** - Variable yang diperlukan tidak ada

## 5. Quick Fix

Jika masih error, coba:
1. Clear cache: `railway run php artisan view:clear`
2. Clear config: `railway run php artisan config:clear`
3. Rebuild: Push commit baru untuk trigger rebuild

