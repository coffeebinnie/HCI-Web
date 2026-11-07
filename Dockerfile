
# Stage 1: Build frontend assets
FROM node:22-bookworm AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# Stage 2: Build Laravel application
FROM php:8.5-cli-bookworm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libicu-dev \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql mbstring bcmath exif pcntl gd zip intl opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY --from=frontend /app/public/build ./public/build

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD sh -c "php artisan migrate --force && php artisan config:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"
