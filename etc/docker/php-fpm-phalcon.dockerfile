ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer
FROM php:${PHP_VERSION}-fpm

RUN apt-get update

RUN apt-get install -y zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN mv "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini"

ARG PSR_VERSION
ARG PHALCON_VERSION

WORKDIR /usr/src/php/ext

RUN curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && tar -xzf v${PSR_VERSION}.tar.gz
RUN curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && tar -xzf v${PHALCON_VERSION}.tar.gz
RUN docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) php-psr-${PSR_VERSION} cphalcon-${PHALCON_VERSION}/build/php7/64bits
RUN rm -r v${PSR_VERSION}.tar.gz v${PHALCON_VERSION}.tar.gz php-psr-${PSR_VERSION} cphalcon-${PHALCON_VERSION}

RUN pecl install xdebug

RUN apt-get install -y libmemcached-dev zlib1g-dev && pecl install memcached

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev && pecl install mongodb
