ARG COMPOSER_VERSION
ARG PHP_VERSION

FROM composer:${COMPOSER_VERSION} AS composer
FROM php:${PHP_VERSION}-fpm AS base

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get install -y zip && \
    apt-get install -y libmemcached-dev zlib1g-dev && pecl install memcached && \
    apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev && pecl install mongodb

WORKDIR /usr/src/php/ext

ARG PSR_VERSION
ARG PHALCON_VERSION

RUN curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && tar -xzf v${PSR_VERSION}.tar.gz && \
    curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && tar -xzf v${PHALCON_VERSION}.tar.gz && \
    docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) php-psr-${PSR_VERSION} cphalcon-${PHALCON_VERSION}/build/php7/64bits && \
    rm -r v${PSR_VERSION}.tar.gz v${PHALCON_VERSION}.tar.gz php-psr-${PSR_VERSION} cphalcon-${PHALCON_VERSION}

FROM base AS development

RUN pecl install xdebug && mv "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"

FROM base AS production

RUN mv "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini"
