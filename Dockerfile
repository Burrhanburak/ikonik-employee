# Multi-stage build for Laravel with Vite
FROM oven/bun:1-alpine AS node-build

WORKDIR /app

# Copy package files
COPY package.json ./
COPY bun.lock* ./

# Install dependencies and build
RUN rm -rf node_modules
RUN bun install
COPY . .
RUN bun run build

# PHP stage
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application files
COPY . .
COPY --from=node-build /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000