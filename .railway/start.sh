#!/bin/bash
set -e

echo "🚀 Starting Railway application..."

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
chmod -R 775 storage || true
chmod -R 775 bootstrap/cache || true
chmod -R 775 public/uploads || true

# Create storage link if not exists
echo "🔗 Creating storage link..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link || echo "⚠️ Storage link creation failed"
else
    echo "✅ Storage link already exists"
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Start the application
echo "✅ Starting PHP server..."
exec php artisan serve --host=0.0.0.0 --port=$PORT

