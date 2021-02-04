FROM php:7.4-fpm-alpine

RUN apk update && apk add autoconf g++ make \
    && wget http://xdebug.org/files/xdebug-3.0.2.tgz \
    && tar -xzvf xdebug-3.0.2.tgz \
    && cd xdebug-3.0.2 \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable xdebug

RUN apk add postgresql-dev fcgi \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY ./development/php-fpm/entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
