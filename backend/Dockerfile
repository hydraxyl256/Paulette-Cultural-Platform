# Base PHP image
FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_sqlite zip gd bcmath

# Install ImageMagick for PDF processing
RUN pecl install imagick && docker-php-ext-enable imagick

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create storage directories
RUN mkdir -p storage/logs storage/app/public storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# Create SQLite database
RUN touch database/database.sqlite

# Expose port
EXPOSE 8000

# Start Laravel server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000