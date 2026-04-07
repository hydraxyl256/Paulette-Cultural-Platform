#!/bin/sh

echo "Starting Laravel app..."

cd /app/backend

# Ensure database exists (absolute safety)
mkdir -p database
touch database/database.sqlite

# Fix permissions
chmod -R 775 storage bootstrap/cache database

# Clear cache
php artisan optimize:clear

# Run migrations
php artisan migrate --force

# Run seeders
php artisan db:seed --force

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT