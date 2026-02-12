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

# Install PHP extensions
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

# Copy Vite build
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Environment
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=single \
    PORT=10000

# Fix permissions
RUN mkdir -p storage/logs storage/app/uploads bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

################################################
# CLEAN NGINX CONFIG (IMPORTANT FIX)
################################################

RUN rm -rf /etc/nginx/conf.d/*
RUN rm -f /etc/nginx/nginx.conf

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

################################################
# SUPERVISOR CONFIG
################################################

RUN mkdir -p /etc/supervisor/conf.d

COPY <<EOF /etc/supervisor/conf.d/supervisord.conf
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

################################################
# START SCRIPT
################################################

COPY <<EOF /usr/local/bin/start.sh
#!/bin/sh
set -e

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

if [ -z "\$APP_KEY" ]; then
    php artisan key:generate
fi

php artisan migrate --force || true

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
EOF

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]

