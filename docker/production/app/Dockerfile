FROM php:7-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql \
    && docker-php-ext-install zip


RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client git zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN usermod -u 1000 www-data

COPY ./ /var/www
COPY ./production.env /var/www/.env

WORKDIR /var/www

RUN chown -R www-data storage \
    && chmod -R 0770 storage \
    && chmod -R 0770 bootstrap/cache

CMD ["php-fpm"]

EXPOSE 9000
