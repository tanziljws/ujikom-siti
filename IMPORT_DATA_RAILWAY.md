# Import Data SQL ke Railway

## Cara 1: Import Langsung via MySQL Command (RECOMMENDED)

### Langkah-langkah:

1. **Upload file SQL ke Railway** (atau copy isinya):
   ```bash
   # Copy file SQL ke Railway
   railway run bash
   # Di dalam Railway shell, buat file:
   cat > galeri_edu.sql << 'EOF'
   [paste isi file SQL di sini]
   EOF
   ```

2. **Import via MySQL command**:
   ```bash
   railway run mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < galeri_edu.sql
   ```

   Atau jika sudah di dalam Railway shell:
   ```bash
   mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < galeri_edu.sql
   ```

3. **Atau gunakan Railway MySQL connection**:
   ```bash
   railway run mysql -h crossover.proxy.rlwy.net -P 14902 -u root -pjbhERRMobIkaLTxpPFZZVPAxpwRolhpd railway < galeri_edu.sql
   ```

## Cara 2: Import via Artisan Command

1. **Deploy command yang sudah dibuat**:
   ```bash
   git add .
   git commit -m "Add SQL import command"
   git push
   ```

2. **Upload file SQL ke Railway**:
   ```bash
   # Copy file ke Railway
   railway run bash
   # Upload file atau copy isinya
   ```

3. **Jalankan command**:
   ```bash
   railway run php artisan db:import-sql galeri_edu.sql --force
   ```

## Cara 3: Import via Railway MySQL Connection (Terminal)

1. **Connect ke Railway MySQL**:
   ```bash
   railway run mysql -h crossover.proxy.rlwy.net -P 14902 -u root -pjbhERRMobIkaLTxpPFZZVPAxpwRolhpd railway
   ```

2. **Di dalam MySQL shell, source file**:
   ```sql
   source galeri_edu.sql;
   ```

   Atau jika file di local, copy isinya dan paste di MySQL shell.

## Cara 4: Import via phpMyAdmin (Jika tersedia)

1. Buka Railway MySQL connection di phpMyAdmin
2. Pilih database `railway`
3. Klik tab "Import"
4. Upload file `galeri_edu (3).sql`
5. Klik "Go"

## Catatan Penting:

- **Backup dulu** jika ada data penting
- File SQL ini akan mengisi data untuk:
  - `agenda` (12 records)
  - `foto` (16 records)
  - `galery` (16 records)
  - `informasi` (6 records)
  - `kategori` (4 records)
  - `posts` (16 records)
  - `site_settings` (79 records)
  - `users` (7 records)
  - `petugas` (3 records)
  - Dan tabel lainnya

- Jika ada error foreign key constraint, pastikan tabel sudah dibuat via migration
- Gunakan `--force` flag jika ingin overwrite data yang sudah ada

## Troubleshooting:

Jika error "Table doesn't exist":
```bash
# Pastikan migration sudah jalan
railway run php artisan migrate --force
```

Jika error "Duplicate entry":
```bash
# Truncate tabel dulu (HATI-HATI!)
railway run php artisan tinker
# Di tinker:
DB::table('agenda')->truncate();
DB::table('foto')->truncate();
# dst...
```

