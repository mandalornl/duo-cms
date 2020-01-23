FROM php:7.2-apache

ENV APACHE_DOCUMENT_ROOT=/app/web
ENV SYMFONY_ENV=dev

RUN apt-get update -y && \
    apt-get install -y \
        libicu-dev \
        zlib1g-dev \
        libmemcached-dev \
        libmagickwand-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        unzip \
        git && \
    curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/ && \
    docker-php-ext-install intl zip pdo_mysql gd && \
    pecl install imagick && \
    pecl install memcached && \
    docker-php-ext-enable imagick memcached && \
    sed -ri -e 's!Listen 80!Listen 8000!' /etc/apache2/ports.conf && \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' -e 's!\*:80!*:8000!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' -e 's!\*:80!*:8000!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    usermod -u 1000 www-data && \
    mkdir -p /app/var && \
    chown www-data:www-data /app/var /var/www

RUN apt-get update && apt-get install -y gnupg2 && \
    curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn && \
    rm -rf /var/lib/apt/lists/*

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

USER www-data

VOLUME /app/var

WORKDIR /app
