# Cara Import Data SQL ke Railway (SIMPLE)

## Cara Termudah: Via Artisan Command

### Langkah 1: Deploy File SQL ke Railway

File SQL sudah ada di repo, jadi cukup push:

```bash
git add "galeri_edu (3).sql"
git commit -m "Add SQL data file"
git push
```

### Langkah 2: Import via Artisan

Setelah deploy, jalankan:

```bash
railway run php artisan db:import-sql "galeri_edu (3).sql" --force
```

## Alternatif: Import Manual via MySQL

### Langkah 1: Copy isi file SQL

Buka file `galeri_edu (3).sql` dan copy semua isinya.

### Langkah 2: Connect ke Railway MySQL

```bash
railway run mysql -h crossover.proxy.rlwy.net -P 14902 -u root -pjbhERRMobIkaLTxpPFZZVPAxpwRolhpd railway
```

### Langkah 3: Paste dan execute

Di dalam MySQL shell, paste semua isi file SQL dan tekan Enter.

Atau jika sudah di Railway shell:

```bash
railway run bash
# Di dalam shell:
cat > /tmp/data.sql << 'EOF'
[paste isi SQL di sini]
EOF

mysql -h crossover.proxy.rlwy.net -P 14902 -u root -pjbhERRMobIkaLTxpPFZZVPAxpwRolhpd railway < /tmp/data.sql
```

## Cara Paling Mudah: Via Railway Dashboard

1. Buka Railway Dashboard
2. Pilih project
3. Pilih MySQL service
4. Klik "Connect" atau "Query"
5. Copy-paste isi file SQL
6. Execute

