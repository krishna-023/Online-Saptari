########################################
# Stage 1 - Build Frontend (Vite)
########################################
FROM node:18-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


########################################
# Stage 2 - Backend (Laravel + PHP)
########################################
FROM php:8.2-fpm-alpine

########################################
# Install System Dependencies
########################################
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    zip \
    nginx \
    supervisor \
    bash

########################################
# Install PHP Extensions
########################################
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
        exif \
        intl \
        gd \
        opcache

########################################
# Install Composer
########################################
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

########################################
# Copy Application
########################################
COPY . .
COPY --from=frontend /app/public/build ./public/build

########################################
# Install Laravel Dependencies
########################################
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

########################################
# Environment
########################################
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=single \
    PORT=10000

########################################
# Fix Laravel Permissions (CRITICAL)
########################################
RUN mkdir -p \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

########################################
# Clean Nginx Default Config
########################################
RUN rm -rf /etc/nginx/conf.d/* \
    && rm -f /etc/nginx/nginx.conf

########################################
# Nginx Config (Production Safe)
########################################
COPY <<EOF /etc/nginx/nginx.conf
worker_processes 1;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    access_log /dev/stdout;
    error_log /dev/stderr;

    sendfile on;
    keepalive_timeout 65;

    server {
        listen 10000;
        server_name _;

        root /var/www/public;
        index index.php index.html;

        location / {
            try_files \$uri \$uri/ /index.php?\$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        }

        location ~ /\. {
            deny all;
        }
    }
}
EOF

########################################
# Supervisor Config
########################################
RUN mkdir -p /etc/supervisor/conf.d

COPY <<EOF /etc/supervisor/conf.d/supervisord.conf
[supervisord]
nodaemon=true
user=root

[program:php-fpm]
command=php-fpm -F
autorestart=true
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr

[program:nginx]
command=nginx -g "daemon off;"
autorestart=true
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr
EOF

########################################
# Start Script (SAFE ORDER)
########################################
COPY <<EOF /usr/local/bin/start.sh
#!/bin/sh
set -e

# Clear any old caches
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Ensure APP_KEY exists
if [ -z "\$APP_KEY" ]; then
    php artisan key:generate
fi

# Run migrations safely
php artisan migrate --force || true

# Rebuild caches AFTER env is ready
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
EOF

RUN chmod +x /usr/local/bin/start.sh

########################################
# Expose Port
########################################
EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]
