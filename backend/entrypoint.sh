#!/bin/sh

echo "Starting Laravel app..."

# Ensure database exists
mkdir -p database
touch database/database.sqlite

# Permissions
chmod -R 775 storage bootstrap/cache database

# Clear caches (IMPORTANT for Filament)
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Start server
php artisan serve --host=0.0.0.0 --port=$PORT