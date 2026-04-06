FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libmagickwand-dev \
    mysql-client \
    redis-tools \
    supervisor \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo_mysql \
    redis \
    bcmath

# Install ImageMagick for PDF processing
RUN pecl install imagick && docker-php-ext-enable imagick

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create required directories
RUN mkdir -p storage/logs storage/app/public storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY docker/supervisor.conf /etc/supervisor/conf.d/laravel.conf

# Expose ports
EXPOSE 80 443 6001

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD php-fpm-healthcheck || exit 1

# Start services
CMD ["/bin/bash", "-c", "php artisan migrate --force && php artisan optimize && supervisord -c /etc/supervisor/conf.d/laravel.conf"]
