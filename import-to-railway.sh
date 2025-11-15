#!/bin/bash

# Script untuk import SQL data ke Railway
# Usage: ./import-to-railway.sh

echo "=========================================="
echo "  Import SQL Data ke Railway"
echo "=========================================="
echo ""

# Check if file exists
SQL_FILE="galeri_edu (3).sql"
if [ ! -f "$SQL_FILE" ]; then
    echo "❌ Error: File $SQL_FILE tidak ditemukan!"
    exit 1
fi

echo "✅ File ditemukan: $SQL_FILE"
echo ""

# Upload file ke Railway dulu, lalu import
echo "📤 Upload file ke Railway..."
railway run bash -c "cat > /tmp/galeri_edu.sql" < "$SQL_FILE"

if [ $? -ne 0 ]; then
    echo "❌ Upload gagal! Mencoba cara alternatif..."
    echo ""
    echo "Cara alternatif: Import via Artisan Command"
    echo "1. Deploy file SQL ke Railway (push ke git)"
    echo "2. Jalankan: railway run php artisan db:import-sql 'galeri_edu (3).sql' --force"
    exit 1
fi

echo "✅ File berhasil diupload"
echo ""

# Import via Railway MySQL
echo "📥 Mengimport data via Railway MySQL..."
railway run bash -c "mysql -h crossover.proxy.rlwy.net -P 14902 -u root -pjbhERRMobIkaLTxpPFZZVPAxpwRolhpd railway < /tmp/galeri_edu.sql"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Import berhasil!"
    echo ""
    echo "Data yang diimport:"
    echo "  - agenda: 12 records"
    echo "  - foto: 16 records"
    echo "  - galery: 16 records"
    echo "  - informasi: 6 records"
    echo "  - kategori: 4 records"
    echo "  - posts: 16 records"
    echo "  - site_settings: 79 records"
    echo "  - users: 7 records"
    echo "  - petugas: 3 records"
    echo ""
else
    echo ""
    echo "❌ Import gagal! Coba cara alternatif:"
    echo ""
    echo "1. Deploy file SQL ke Railway (git push)"
    echo "2. Jalankan: railway run php artisan db:import-sql 'galeri_edu (3).sql' --force"
    exit 1
fi

