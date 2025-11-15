# Railway Storage Link Fix

## Masalah
Symlink storage tidak dibuat saat deployment di Railway, menyebabkan file yang disimpan di `storage/app/public/` tidak bisa diakses.

## Solusi

### 1. nixpacks.toml
Sudah ditambahkan di build phase:
```toml
[phases.build]
cmds = [
  "mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs public/uploads/galeri bootstrap/cache",
  "chmod -R 775 storage bootstrap/cache public/uploads || true",
  "php artisan storage:link || true",
  ...
]
```

### 2. Procfile
Sudah diupdate untuk create storage link saat start:
```
web: bash -c "mkdir -p ... && php artisan storage:link 2>/dev/null || true && php artisan serve ..."
```

### 3. Manual Fix (jika masih tidak bekerja)

Jalankan di Railway Console:
```bash
railway run php artisan storage:link
```

Atau via Railway CLI:
```bash
railway run bash -c "php artisan storage:link"
```

## Catatan Penting

**File foto gallery disimpan di `public/uploads/galeri/`, BUKAN di `storage/app/public/`.**

Jadi storage link **TIDAK diperlukan** untuk foto gallery yang sudah ada.

Storage link hanya diperlukan jika:
- File disimpan via `Storage::disk('public')->put(...)`
- File disimpan di `storage/app/public/`
- File perlu diakses via `/storage/...` URL

## Troubleshooting

### Cek apakah storage link ada:
```bash
railway run ls -la public/ | grep storage
```

### Cek apakah file foto ada:
```bash
railway run ls -la public/uploads/galeri/ | head -20
```

### Test akses file:
- Foto gallery: `https://ujikom-siti-production.up.railway.app/uploads/galeri/1762991881_0.jpeg`
- Storage file: `https://ujikom-siti-production.up.railway.app/storage/...`

## Jika File Foto Tidak Ada

File foto perlu di-commit ke git atau di-upload ke Railway:

1. **Via Git (Recommended):**
   ```bash
   git add public/uploads/galeri/
   git commit -m "Add gallery photos"
   git push
   ```

2. **Via Railway Console:**
   ```bash
   # Upload file ke Railway
   railway run bash
   # Lalu upload file via scp atau Railway file upload
   ```

3. **Via Railway Volume (Persistent Storage):**
   - Buat Railway Volume untuk `public/uploads/`
   - File akan persist across deployments

