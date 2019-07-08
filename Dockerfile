FROM php:7-alpine

ENV PHP_EXT="\
    pgsql \
    pdo_pgsql \
"

RUN apk --no-cache add postgresql-dev && \
    docker-php-ext-install ${PHP_EXT}

WORKDIR /var/www

CMD [ "php", "-S", "0.0.0.0:8080", "-t", "public" ]