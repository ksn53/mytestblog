FROM php:7.4-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        mc \
        sudo \
        libfreetype6-dev \
        libssl-dev \
        libjpeg62-turbo-dev \
        libmemcached-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
    && docker-php-ext-install -j$(nproc) iconv sockets pdo_mysql mysqli pcntl intl bcmath \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) sysvsem \
    && docker-php-ext-install soap \
    && docker-php-ext-enable opcache

RUN pecl install imagick xdebug-2.9.0 \
    && docker-php-ext-enable imagick

RUN mkdir -p /var/log/php-fpm
RUN ln -sf /dev/stdout /var/log/php-fpm/access.log
RUN ln -sf /dev/stderr /var/log/php-fpm/error.log

RUN mkdir -p /var/log/php-fpm

RUN curl -sS https://getcomposer.org/installer | php -- --1 --install-dir=/usr/local/bin --filename=composer

# устанавливаем пакеты компоузера в образ
#RUN mkdir /var/composer
#COPY ./src/composer.lock /var/composer/composer.lock
#COPY ./src/composer.json /var/composer/composer.json

WORKDIR /var/www

#ENTRYPOINT /var/www/make-environment.sh

CMD ["php-fpm"]
