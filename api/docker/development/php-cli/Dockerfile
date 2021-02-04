FROM php:7.4-cli-alpine

RUN apk add --no-cache autoconf g++ make \
    && wget http://xdebug.org/files/xdebug-3.0.2.tgz \
    && tar -xzvf xdebug-3.0.2.tgz \
    && cd xdebug-3.0.2 \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable xdebug

RUN apk add postgresql-dev bash coreutils \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d

COPY ./common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

RUN apk add unzip

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && rm -rf /root/.composer/cache

WORKDIR /app
