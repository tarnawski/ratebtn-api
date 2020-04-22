FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y libicu-dev libzip-dev zip git g++ \
    && docker-php-ext-install intl zip pdo pdo_mysql

RUN pecl install mongodb && docker-php-ext-enable mongodb