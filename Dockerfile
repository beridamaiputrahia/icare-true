FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite

# Default PHP upload_max_filesize (2M) & post_max_size (8M) lebih kecil dari
# validasi upload foto album (max:8192 KB per foto, hingga 30 foto sekaligus).
# Tanpa ini, PHP mengosongkan file yang terlalu besar dari $_FILES sebelum
# sampai ke Laravel, sehingga upload tampak "berhasil" tapi foto tidak
# tersimpan -- tidak ada error yang terlihat oleh user.
RUN { \
    echo "upload_max_filesize = 10M"; \
    echo "post_max_size = 260M"; \
    echo "max_file_uploads = 30"; \
    } > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm ci \
    && npm run build \
    && npm cache clean --force

RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
