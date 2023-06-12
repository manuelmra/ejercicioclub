# Use the official PHP 8.x image for arm64 architecture
FROM php:8.0-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev

# Install PHP extensions
RUN docker-php-ext-install \
    zip \
    gd \
    pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory
WORKDIR /var/www/html

# Copy the project files to the working directory
COPY . .

# Install project dependencies using Composer
#RUN composer install --no-dev --optimize-autoloader --no-interaction

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start the PHP-FPM server
CMD ["php-fpm"]
