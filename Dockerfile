FROM php:8-fpm as base

RUN apt-get update

RUN apt-get install -y --no-install-recommends libpq-dev

RUN apt-get install -y \
      curl \
      libmagickwand-dev \
      libpq-dev \
      libmagick++-dev \
      libwebp-dev \
      libjpeg-dev \
      libpng-dev \
      libfreetype6-dev \
      libssl-dev \
      libmcrypt-dev \
      libxpm-dev \
      nano \
      git \
      dnsutils

RUN apt-get -yqq install exiftool

RUN docker-php-ext-configure exif \
  && docker-php-ext-configure exif \
  && docker-php-ext-install exif \
  && docker-php-ext-enable exif \
  && docker-php-ext-install bcmath \
  && docker-php-ext-install bz2 \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install opcache \
  && docker-php-ext-install iconv

RUN docker-php-ext-enable opcache

RUN docker-php-ext-configure gd \
  --with-jpeg \
  --with-freetype \
  --with-webp \
  --with-xpm \
  && docker-php-ext-install gd

RUN docker-php-ext-install mysqli pdo_pgsql pdo_mysql

#RUN curl -s http://getcomposer.org/installer | php && \
#    mv composer.phar /usr/local/bin/composer

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

FROM base as dev
ENV XDEBUG_CONF=/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN apk add --no-cache -t .deps $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

