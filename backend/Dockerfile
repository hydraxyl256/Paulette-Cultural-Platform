# =========================
# Base image
# =========================
FROM php:8.2-fpm

# =========================
# Install system dependencies
# =========================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsqlite3-dev \
    sqlite3 \
    libicu-dev \
    libonig-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo pdo_sqlite zip gd bcmath intl

# =========================
# Install Composer
# =========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =========================
# Set working directory
# =========================
WORKDIR /app

# =========================
# Copy app source
# =========================
COPY . .

# =========================
# Install PHP dependencies
# =========================
RUN composer install --optimize-autoloader --no-dev

# =========================
# Install Node dependencies and build Tailwind CSS
# =========================

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Node.js dependencies
RUN npm install && npm run build

# =========================
# Create required directories for Laravel
# =========================
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} \
    && chmod -R 775 storage bootstrap/cache

# =========================
# Create SQLite database if missing
# =========================
RUN touch database/database.sqlite

# =========================
# Expose port
# =========================
EXPOSE 10000

# =========================
# Run migrations and start server
# =========================
COPY entrypoint.sh /app/entrypoint.sh
CMD ["/app/entrypoint.sh"]