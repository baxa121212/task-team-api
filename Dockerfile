FROM php:8.2-fpm

# System deps + SQLite deps
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions (MySQL + SQLite + common)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Install deps
RUN composer install --no-dev --optimize-autoloader

# Create SQLite file (important)
RUN mkdir -p /var/www/storage \
 && touch /var/www/storage/database.sqlite

# Permissions (Laravel needs write access)
RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

# IMPORTANT: do NOT generate APP_KEY here. Set APP_KEY in Render env.
CMD ["sh","-c","php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
