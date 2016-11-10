FROM php:7.0-fpm

# packets
RUN apt-get update \
    && apt-get install -y \
    zlib1g-dev \
    unzip \
    ssmtp \
    mysql-client \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng12-dev \
    sqlite3

# php extension
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) pdo pdo_mysql iconv gd

RUN usermod -u 1000 www-data

# sendmail php.ini
RUN echo "sendmail_path = /usr/sbin/ssmtp -t" >> /usr/local/etc/php/php.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000

WORKDIR /srv/http

CMD ["php-fpm"]
