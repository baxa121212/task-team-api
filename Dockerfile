FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev \
    sqlite3 libsqlite3-dev \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo pdo_mysql pdo_sqlite \
    mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

# IMPORTANT: do NOT generate APP_KEY here. Set APP_KEY in Render env.
CMD ["sh","-c", "\
  mkdir -p /var/www/database && touch /var/www/database/database.sqlite && \
  php artisan config:clear && php artisan cache:clear && \
  php artisan migrate --force && \
  php artisan serve --host=0.0.0.0 --port=${PORT:-10000} \
"]
