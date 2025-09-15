FROM php:8.2-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip \
    && docker-php-ext-enable gd zip \
    && rm -rf /var/lib/apt/lists/*


# Устанавливаем composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/bravo_app
