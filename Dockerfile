# Stage 1 - Build Frontend (Vite)
FROM node:18-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + PHP optimized for Render)
FROM php:8.2-fpm-alpine

# Install system dependencies (minimal for Render)
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
RUN docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy app files
COPY . .

# Copy built frontend from Stage 1
COPY --from=frontend /app/public/dist ./public/dist

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set environment variables
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=single \
    PORT=10000

# Set permissions
RUN chown -R nobody:nobody /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Create necessary directories
RUN mkdir -p storage/logs storage/app/uploads \
    && chown -R nobody:nobody storage

# Configure Nginx
RUN mkdir -p /var/run/nginx && \
    rm -f /etc/nginx/conf.d/default.conf
COPY --chown=nobody:nobody <<EOF /etc/nginx/conf.d/default.conf
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
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\. {
        deny all;
    }
}
EOF

# Configure Supervisor for process management
RUN mkdir -p /etc/supervisor/conf.d
COPY --chown=root:root <<EOF /etc/supervisor/conf.d/laravel.conf
[supervisord]
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid
nodaemon=true
user=root

[program:php-fpm]
command=php-fpm -F
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
autorestart=true

[program:nginx]
command=nginx -g "daemon off;"
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
autorestart=true
EOF

# Startup script for migrations and cache
COPY --chown=nobody:nobody <<EOF /usr/local/bin/start.sh
#!/bin/bash
set -e

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Start supervisor
exec supervisord -c /etc/supervisor/conf.d/laravel.conf
EOF

RUN chmod +x /usr/local/bin/start.sh

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://127.0.0.1:10000/health || exit 1

EXPOSE 10000

USER nobody

CMD ["/usr/local/bin/start.sh"]
