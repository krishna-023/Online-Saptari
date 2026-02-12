########################################
# Stage 1 - Build Frontend (Vite)
########################################
FROM node:18-alpine AS frontend

WORKDIR /app

# Install dependencies first (better caching)
COPY package*.json ./
RUN npm ci

# Copy project files
COPY . .

# Build Vite assets
RUN npm run build


########################################
# Stage 2 - Backend (Laravel + PHP)
########################################
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libpq-dev \
    oniguruma-dev \
    libzip-dev \
    nginx \
    supervisor \
    bash

# Install PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    zip \
    unzip

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


# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel project
COPY . .

# 🔥 FIXED: Copy correct Vite build folder
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Environment variables
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=single \
    PORT=10000

# Create required directories
RUN mkdir -p storage/logs storage/app/uploads bootstrap/cache \
    && chown -R nobody:nobody /var/www \
    && chmod -R 775 storage bootstrap/cache

########################################
# NGINX Configuration
########################################
RUN mkdir -p /var/run/nginx \
    && rm -f /etc/nginx/conf.d/default.conf

COPY <<EOF /etc/nginx/conf.d/default.conf
server {
    listen 10000;
    server_name _;
    root /var/www/public;
    index index.php;

    client_max_body_size 100M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
EOF


########################################
# Supervisor Configuration
########################################
RUN mkdir -p /etc/supervisor/conf.d

COPY <<EOF /etc/supervisor/conf.d/laravel.conf
[supervisord]
nodaemon=true

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
# Startup Script
########################################
COPY <<EOF /usr/local/bin/start.sh
#!/bin/sh
set -e

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ -z "\$APP_KEY" ]; then
    php artisan key:generate
fi

php artisan migrate --force || true

exec supervisord -c /etc/supervisor/conf.d/laravel.conf
EOF

RUN chmod +x /usr/local/bin/start.sh

########################################
# Health Check
########################################
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://127.0.0.1:10000 || exit 1

EXPOSE 10000

USER nobody

CMD ["/usr/local/bin/start.sh"]
