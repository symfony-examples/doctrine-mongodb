ARG PHP_VERSION=8.2.0
ARG COMPOSER_VERSION=2.5.5

FROM composer:${COMPOSER_VERSION} as composer

FROM php:${PHP_VERSION}-fpm-alpine as builder

### SYMFONY REQUIREMENT
RUN apk add --no-cache icu-dev \
  && docker-php-ext-install intl \
  && docker-php-ext-enable intl \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

COPY .docker/php/symfony.ini /usr/local/etc/php/conf.d/
### END SYMFONY REQUIREMENT

COPY --from=composer /usr/bin/composer /usr/bin/composer

CMD ["php-fpm", "-F"]

EXPOSE 9000

WORKDIR /var/www/symfony

FROM builder as local

## symfony cli install
RUN apk add --no-cache bash git
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli
## END symfony cli install

HEALTHCHECK --interval=5s --timeout=3s --retries=3 CMD symfony check:req

## INSTALL MONGODB DRIVER
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS openssl curl-dev openssl-dev \
    && pecl install mongodb

COPY ./.docker/php/mongodb.ini /usr/local/etc/php/conf.d/

FROM builder as ci
ENV APP_ENV=test

### INSTALL DEPENDENCIES DEV REQUIREMENTS
RUN set -eux; \
    composer install --prefer-dist --no-progress --no-scripts --no-interaction --optimize-autoloader;

### COPY ADDITIONAL PROJECT FILES AND DIRECTORY
COPY bin bin/
COPY config config/
COPY migrations migrations/
COPY public public/
COPY src src/
COPY templates templates/
COPY tests tests/
COPY .env ./ .env.test .php-cs-fixer.dist.php phpstan.neon phpunit.xml.dist ./

### RUN COMPOSER SCRIPTS AND CLEAR CAHE
RUN set -eux; \
    composer run-script post-install-cmd \
    composer clear-cache

## ClEAN
RUN rm -rf /tmp/* /var/cache/apk/* /var/tmp/*
