FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libsqlite3-dev zip unzip curl \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && chmod -R 777 storage bootstrap/cache \
    && chmod 664 database/database.sqlite

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
