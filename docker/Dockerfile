# Stage 1: Build environment and Composer dependencies
FROM php:8.5-fpm-alpine AS builder

# Install PHP libraries
RUN apk add --no-cache \
    git \
    unzip \
    postgresql-dev \
    libxml2-dev \
    icu-dev \
    libzip-dev \
    autoconf \
    g++ \
    make \
    nodejs \
    npm \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        intl \
        zip \
        bcmath \
        soap \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Needed so artisan can boot during build
RUN if [ ! -f .env ] && [ -f .env.example ]; then \
    cp .env.example .env; \
fi

ARG APP_NAME="Mini HRMS Rebuild"
ENV APP_NAME=$APP_NAME
ENV VITE_APP_NAME=$APP_NAME

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    && npm ci

RUN composer dump-autoload --optimize \
    && npm run build \
    && npm cache clean --force

# Stage 2: Production environment
FROM php:8.5-fpm-alpine AS php-fpm

LABEL org.opencontainers.image.source="https://github.com/idjiot-sandwiches/mini-hrms-rebuild"
LABEL org.opencontainers.image.title="Mini HRMS Rebuild PHP"
LABEL org.opencontainers.image.description="Laravel PHP-FPM production image"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.url="https://github.com/idjiot-sandwiches/mini-hrms-rebuild"
LABEL org.opencontainers.image.vendor="Idjiot Sandwiches"

# Install only runtime libraries needed in production
# libfcgi-bin and procps are required for the php-fpm-healthcheck script
RUN apk add --no-cache \
    libpq \
    icu-libs \
    libzip \
    fcgi \
    procps \
    curl

# Download and install php-fpm health check script
RUN curl -o /usr/local/bin/php-fpm-healthcheck \
    https://raw.githubusercontent.com/renatomefi/php-fpm-healthcheck/master/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

# Copy the initialization script
COPY ./docker/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy the initial storage structure
COPY ./storage /var/www/storage-init

# Copy PHP extensions and libraries from the builder stage
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder /usr/local/bin/docker-php-ext-* /usr/local/bin/

# Use the recommended production PHP configuration
# -----------------------------------------------------------
# PHP provides development and production configurations.
# Here, we replace the default php.ini with the production
# version to apply settings optimized for performance and
# security in a live environment.
# -----------------------------------------------------------
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Keep the image-provided FPM global config intact and add pool overrides separately
COPY ./docker/php-fpm/conf.d/*.conf /usr/local/etc/php-fpm.d/
# Update the variables_order to include E (for ENV)
#RUN sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini"

# Copy the application code and dependencies from the build stage
COPY --from=builder /var/www /var/www

# Set working directory
WORKDIR /var/www

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www

# Switch to the non-privileged user to run the application
USER www-data

# Change the default command to run the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

# Stage 3: Dedicated Scheduler Service
FROM php-fpm AS scheduler

LABEL org.opencontainers.image.source="https://github.com/idjiot-sandwiches/mini-hrms-rebuild"
LABEL org.opencontainers.image.title="Mini HRMS Rebuild Scheduler"
LABEL org.opencontainers.image.description="Scheduler for Mini HRMS Rebuild"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.url="https://github.com/idjiot-sandwiches/mini-hrms-rebuild"
LABEL org.opencontainers.image.vendor="Idjiot Sandwiches"

# Run Laravel's built-in scheduler loop
ENTRYPOINT []
CMD ["php", "artisan", "schedule:work"]

# Stage 4: Nginx production image
FROM nginx:alpine AS web

LABEL org.opencontainers.image.source="https://github.com/idjiot-sandwiches/mini-hrms-rebuild"
LABEL org.opencontainers.image.title="Mini HRMS Rebuild Web"
LABEL org.opencontainers.image.description="Nginx frontend image for Mini HRMS Rebuild"
LABEL org.opencontainers.image.licenses="MIT"
LABEL org.opencontainers.image.url="https://github.com/idjiot-sandwiches/mini-hrms-rebuild"
LABEL org.opencontainers.image.vendor="Idjiot Sandwiches"

# Copy custom Nginx configuration
# -----------------------------------------------------------
# Replace the default Nginx configuration with our custom one
# that is optimized for serving a Laravel application.
# -----------------------------------------------------------
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Copy Laravel's public assets from the builder stage
# -----------------------------------------------------------
# We only need the 'public' directory from our Laravel app.
# -----------------------------------------------------------
COPY --from=builder /var/www/public /var/www/public

# Set the working directory to the public folder
WORKDIR /var/www/public

# Expose port 80 and start Nginx
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
