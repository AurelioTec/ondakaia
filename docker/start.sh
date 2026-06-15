#!/bin/sh
set -e

PORT="${PORT:-8080}"

sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:.*>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Create necessary directories first
mkdir -p storage/app/private storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
touch storage/logs/laravel.log
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Run Laravel commands with error handling
php artisan optimize:clear --no-interaction || echo "optimize:clear failed, continuing..."
php artisan storage:link --force --no-interaction || echo "storage:link failed, continuing..."
php artisan migrate --force --no-interaction || echo "migrate failed, continuing..."
php artisan db:seed --force --no-interaction || echo "db:seed failed, continuing..."

# Configure Apache modules
a2dismod mpm_event mpm_worker || true
rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf || true
a2enmod mpm_prefork || true

exec apache2-foreground
