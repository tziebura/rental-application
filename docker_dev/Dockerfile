FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
      zlib1g-dev \
      g++ git libicu-dev zip \
      libzip-dev \
      zip \
      librabbitmq-dev \
      libssh-dev \
    && docker-php-ext-install intl opcache pdo_mysql bcmath sockets sysvsem \
    && pecl install apcu amqp xdebug \
    && docker-php-ext-enable apcu amqp \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

WORKDIR /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app