#!/bin/sh
set -e

# 1. Initialize storage directory if empty
if [ ! "$(ls -A /var/www/storage)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/storage-init/. /var/www/storage
fi

# Clean up initial storage copy source
rm -rf /var/www/storage-init

# 2. Ensure framework subdirectories exist
mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/bootstrap/cache

# 3. Always enforce correct permissions (runs on every startup)
echo "Setting permissions for storage and bootstrap/cache..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 4. Run Laravel migrations & caches
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Execute main process
exec "$@"
