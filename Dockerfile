# Use PHP 8.2 with Apache (Laravel 11 requires PHP >= 8.2)
FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git libpng-dev libonig-dev libxml2-dev

# Install required PHP extensions for Laravel 11
RUN docker-php-ext-install pdo pdo_mysql zip bcmath gd pcntl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel project files
COPY . /var/www/html

# Install dependencies optimized for production
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Add Apache configuration for Laravel
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/laravel.conf

RUN a2enconf laravel
