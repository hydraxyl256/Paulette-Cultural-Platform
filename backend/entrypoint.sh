#!/bin/sh
set -e

# Ensure SQLite exists
mkdir -p database
touch database/database.sqlite

# Clear Laravel caches (IMPORTANT)
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# FIX FILAMENT (THIS IS THE KEY)
php artisan filament:upgrade

# Rebuild caches AFTER everything is ready
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT