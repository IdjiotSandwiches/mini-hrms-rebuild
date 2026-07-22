# ==========================================
# Stage 1: Install Composer Dependencies
# ==========================================
FROM php:8.5-fpm-alpine AS composer-builder

# Install helper and extensions needed for composer scripts / autoload
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    intl \
    zip \
    bcmath \
    soap \
    redis \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www

# Layer Cache Trick: Copy composer descriptors first
COPY composer.json composer.lock ./

# Install vendor packages without app code present
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-autoloader \
    --no-scripts \
    --no-interaction

# Copy full application source code to generate autoloader
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

# ==========================================
# Stage 2: Build Frontend Assets (Node.js)
# ==========================================
FROM node:24-alpine AS frontend-builder
WORKDIR /var/www

# Install PHP CLI so Wayfinder plugin can execute `php artisan`
RUN apk add --no-cache php-cli php-tokenizer php-xml

# Cache NPM packages first
COPY package*.json ./
RUN npm ci

# Copy application source code
COPY . .
# Copy vendor directory from composer-builder so 'php artisan' works during Vite build
COPY --from=composer-builder /var/www/vendor ./vendor

ARG APP_NAME="Mini HRMS Rebuild"
ENV VITE_APP_NAME=$APP_NAME

# Run Vite build (outputs to /var/www/public/build)
RUN npm run build

# ==========================================
# Stage 3: Production PHP-FPM Image
# ==========================================
FROM php:8.5-fpm-alpine AS php-fpm

LABEL org.opencontainers.image.source="https://github.com/idjiot-sandwiches/mini-hrms-rebuild" \
      org.opencontainers.image.title="Mini HRMS Rebuild PHP" \
      org.opencontainers.image.description="Laravel PHP-FPM production image" \
      org.opencontainers.image.licenses="MIT" \
      org.opencontainers.image.url="https://github.com/idjiot-sandwiches/mini-hrms-rebuild" \
      org.opencontainers.image.vendor="Idjiot Sandwiches"

# Install PHP extensions required for production runtime
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    intl \
    zip \
    bcmath \
    soap \
    redis \
    opcache

RUN apk add --no-cache fcgi procps curl

# Download and set up php-fpm health check script
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

COPY ./docker/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use standard production php.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY ./docker/php-fpm/conf.d/*.conf /usr/local/etc/php-fpm.d/

WORKDIR /var/www

# Copy PHP source & vendor files from composer-builder stage
COPY --from=composer-builder /var/www /var/www
# Copy compiled frontend assets from frontend-builder stage
COPY --from=frontend-builder /var/www/public/build /var/www/public/build

COPY ./storage /var/www/storage-init

RUN chown -R www-data:www-data /var/www
USER www-data

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
EXPOSE 9000
CMD ["php-fpm"]

# ==========================================
# Stage 4: Dedicated Scheduler Service
# ==========================================
FROM php-fpm AS scheduler

LABEL org.opencontainers.image.source="https://github.com/idjiot-sandwiches/mini-hrms-rebuild" \
      org.opencontainers.image.title="Mini HRMS Rebuild Scheduler" \
      org.opencontainers.image.description="Scheduler for Mini HRMS Rebuild" \
      org.opencontainers.image.licenses="MIT" \
      org.opencontainers.image.url="https://github.com/idjiot-sandwiches/mini-hrms-rebuild" \
      org.opencontainers.image.vendor="Idjiot Sandwiches"

ENTRYPOINT []
CMD ["php", "artisan", "schedule:work"]

# ==========================================
# Stage 5: Nginx Production Web Image
# ==========================================
FROM nginx:alpine AS web

LABEL org.opencontainers.image.source="https://github.com/idjiot-sandwiches/mini-hrms-rebuild" \
      org.opencontainers.image.title="Mini HRMS Rebuild Web" \
      org.opencontainers.image.description="Nginx frontend image for Mini HRMS Rebuild" \
      org.opencontainers.image.licenses="MIT" \
      org.opencontainers.image.url="https://github.com/idjiot-sandwiches/mini-hrms-rebuild" \
      org.opencontainers.image.vendor="Idjiot Sandwiches"

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
# Copy public directory from php-fpm stage (includes Vite assets and public static files)
COPY --from=php-fpm /var/www/public /var/www/public

WORKDIR /var/www/public
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
