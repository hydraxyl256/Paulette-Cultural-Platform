FROM php:8.2-fpm

# Install system dependencies + SQLite dev
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite zip gd bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create required directories
RUN mkdir -p storage/logs storage/app/public storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# Copy optional configs if you have them
# COPY docker/nginx.conf /etc/nginx/sites-available/default
# COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini
# COPY docker/supervisor.conf /etc/supervisor/conf.d/laravel.conf

# Expose port
EXPOSE 8000

# Start app
CMD ["bash", "-c", "touch database/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"]