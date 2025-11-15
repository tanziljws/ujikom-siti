#!/bin/bash
set -e

echo "🚀 Starting Railway setup..."

# Create necessary directories
echo "📁 Creating directories..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p public/uploads/galeri
mkdir -p bootstrap/cache

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/uploads

# Generate app key if not exists
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force || echo "⚠️ Migration failed, continuing..."

# Create storage link
echo "🔗 Creating storage link..."
# Remove existing link if exists
rm -f public/storage || true
# Create new link
php artisan storage:link || echo "⚠️ Storage link already exists or failed"
# Verify link exists
if [ -L "public/storage" ]; then
    echo "✅ Storage link created successfully"
else
    echo "⚠️ Warning: Storage link may not have been created"
fi

# Clear and cache config
echo "🧹 Clearing and caching config..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "✅ Setup complete!"

