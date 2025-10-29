FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working folder
WORKDIR /var/www/html

# Copy app
COPY . .

# ✅ Copy SSL CA
COPY ./certs/ca.pem /etc/ssl/certs/ca.pem

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create ENV if missing
RUN cp .env.example .env || true

# Generate app key
RUN php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
