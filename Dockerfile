FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    curl \
    ca-certificates

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working folder
WORKDIR /var/www/html

# Copy application files
COPY . .

# ✅ CRITICAL: Copy SSL Certificate BEFORE running composer
# Create directory if it doesn't exist
RUN mkdir -p /etc/ssl/certs

# Copy the certificate
COPY certs/ca.pem /etc/ssl/certs/ca.pem

# Set proper permissions
RUN chmod 644 /etc/ssl/certs/ca.pem

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy .env.example to .env if .env doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Generate application key
RUN php artisan key:generate --force || true

# Create required directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Start command
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=8000
