#!/bin/sh
# backend/entrypoint.sh

# Ensure SQLite file exists
if [ ! -f database/database.sqlite ]; then
    mkdir -p database
    touch database/database.sqlite
fi

# Run Laravel migrations
php artisan migrate --force

# Build Tailwind (optional if you need dynamic rebuild)
npm run build

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=$PORT