#!/bin/bash
set -e

[ -f "artisan" ] || { echo "Fehler: Im Laravel-Projektverzeichnis ausführen."; exit 1; }
[ -f ".env" ] || { echo "Fehler: .env fehlt."; exit 1; }

composer install --no-dev --optimize-autoloader

if [ -f "package.json" ]; then
    npm ci --production 2>/dev/null || npm install --production
    npm run build
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link 2>/dev/null || true

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

echo "Deployment fertig."
