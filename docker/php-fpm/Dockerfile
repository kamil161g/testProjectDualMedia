FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd && \
    chown -R www-data:www-data /var/www


# Ustawienie katalogu roboczego
WORKDIR /var/www

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Eksponowanie portu
EXPOSE 9000

CMD php-fpm
