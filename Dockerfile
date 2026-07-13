FROM dunglas/frankenphp:php8.4-alpine

RUN apk add --no-cache \
    libpng-dev libzip-dev icu-dev oniguruma-dev \
    nodejs curl bash $PHPIZE_DEPS

RUN install-php-extensions pcntl gd intl zip opcache pdo_mysql redis

RUN curl -fsSL https://bun.sh/install | bash \
    && ln -s /root/.bun/bin/bun /usr/local/bin/bun

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN bun install && bun run build

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80 443 443/udp

ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019"]
