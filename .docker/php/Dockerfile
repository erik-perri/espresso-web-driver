FROM php:8.3-fpm

RUN apt update && \
    apt install -y libzip-dev && \
    docker-php-ext-install zip && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /code
