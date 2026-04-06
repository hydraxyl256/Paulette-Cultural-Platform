#!/bin/bash
set -e

# 1. Ensure SQLite file exists
touch database/database.sqlite

# 2. Laravel migrations
php artisan migrate --force

# 3. Laravel config & view caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Build Tailwind / Vite assets
npm install
npm run build   # not 'dev' — this creates production CSS/JS in public/build

# 5. Start the server
php artisan serve --host=0.0.0.0 --port=$PORT