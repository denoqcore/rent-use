FROM dunglas/frankenphp:php8.4-alpine
# PHP
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    $PHPIZE_DEPS

RUN install-php-extensions \
    pcntl \
    gd \
    intl \
    zip \
    opcache \
    pdo_mysql \
    redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Work dir
WORKDIR /app

# Copying files
COPY . .

# Dependencies
RUN composer install --no-interaction --optimize-autoloader

# Root folder Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Ports
EXPOSE 80 443 443/udp

# Octane + FrankenPHP
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019"]
