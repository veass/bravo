FROM php:8.4-fpm

# Обновляем пакеты и устанавливаем необходимые расширения и инструменты
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql opcache zip

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория внутри контейнера
WORKDIR /var/www/html

# Копируем существующий проект (если есть)
# COPY . /var/www/html

# Установка зависимостей через Composer (можно запускать отдельно)
# RUN composer install --prefer-dist --no-dev -o

EXPOSE 9000

CMD ["php-fpm"]
