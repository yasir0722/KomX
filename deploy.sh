#!/bin/bash
# ──────────────────────────────────────────────────────────────────────────────
# KomX — Production Deployment Script
# Run on the DigitalOcean server after git pull
# Usage: bash deploy.sh
# ──────────────────────────────────────────────────────────────────────────────

set -e

echo "🚀 Deploying KomX..."

# Pull latest code
echo "📥 Pulling latest changes..."
git pull origin main

# Install PHP dependencies (no dev)
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build
echo "📦 Installing Node dependencies..."
npm ci
echo "🔨 Building frontend assets..."
npm run build

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Clear and rebuild caches
echo "🧹 Clearing caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue workers
echo "🔄 Restarting queue workers..."
php artisan queue:restart

# Set permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

echo "✅ Deployment complete!"
