FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

ARG USERID=1000
ARG GROUPID=1000

RUN groupadd -g ${GROUPID} appuser && \
    useradd -u ${USERID} -g appuser -m appuser -s /bin/bash

RUN chown -R appuser:appuser /var/www

USER appuser

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

EXPOSE 9000

CMD ["php-fpm"]
