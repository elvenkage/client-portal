#!/bin/bash

# ==========================================================
# Production Deployment Script for Agency Client Portal
# ==========================================================
echo "Starting production deployment process..."

# 1. Install dependencies (Optimized Autoloader, No Dev Packages)
echo "Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# 2. Clear out any stale cached files to prevent conflicts
echo "Clearing system caches..."
php artisan optimize:clear

# 3. Cache Database Configurations
echo "Caching configurations..."
php artisan config:cache

# 4. Cache Event Listeners
echo "Caching events..."
php artisan event:cache

# 5. Cache Routes Array (Drastically speeds up router regex match)
echo "Caching routes..."
php artisan route:cache

# 6. Pre-compile Blade Views (Prevents runtime compilation pauses)
echo "Caching views..."
php artisan view:cache

# 7. Run Database Migrations safely (Force circumvents the prod risk prompt)
echo "Running migrations..."
php artisan migrate --force

echo "=========================================================="
echo "Deployment & Performance optimizations are complete!"
echo "Server is ready for production traffic."
echo "=========================================================="
