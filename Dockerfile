FROM node:22-alpine AS frontend

WORKDIR /build

COPY package.json package.lock ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ resources/
COPY public/ public/

RUN npm run build

FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    gd \
    zip \
    opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

COPY . .

COPY --from=frontend /build/public/build/ public/build/

COPY docker/php/uploads.ini $PHP_INI_DIR/conf.d/uploads.ini
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

EXPOSE 8080

CMD ["/start.sh"]
