#!/bin/sh
set -e

PORT="${PORT:-80}"

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan storage:link || true
php artisan migrate --force

# Seed data awal hanya sekali, saat tabel users masih kosong.
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ]; then
    php artisan db:seed --force
fi

exec apache2-foreground
