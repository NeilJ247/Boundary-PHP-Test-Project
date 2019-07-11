FROM php:7-alpine

ENV PHP_EXT="\
    pgsql \
    pdo_pgsql \
"

RUN apk add --no-cache $PHPIZE_DEPS postgresql-dev && \
    docker-php-ext-install ${PHP_EXT} && \
    yes | pecl install xdebug && \
    docker-php-ext-enable xdebug

WORKDIR /var/www

CMD [ "php", "-S", "0.0.0.0:8080", "-t", "public" ]