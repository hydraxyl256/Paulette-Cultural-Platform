#!/bin/sh

echo "Starting Laravel app..."

cd /app/backend

# Ensure DB
mkdir -p database
touch database/database.sqlite

# Permissions
chmod -R 775 storage bootstrap/cache database

# 🔥 Clear EVERYTHING (important for Vite)
php artisan optimize:clear

# Migrate
php artisan migrate --force

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT