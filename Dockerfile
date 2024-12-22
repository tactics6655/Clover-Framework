FROM php:8-fpm as base

ENV TZ=Asia/Seoul
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="10000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10"

ENV MEMCACHED_DEPS zlib-dev libmemcached-dev cyrus-sasl-dev

RUN apt-get update

RUN apt-get install -y --no-install-recommends libpq-dev

RUN apt-get install -y \
      zip \
      unzip \
      curl \
      libmagickwand-dev \
      libmagick++-dev \
      libwebp-dev \
      libjpeg-dev \
      libpng-dev \
      libfreetype6-dev \
      libssl-dev \
      zlib1g-dev \
      libmcrypt-dev \
      libxpm-dev \
      libzip-dev \
      libzip4 \
      nano \
      git \
      libz-dev \
      libmemcached-tools \
      dnsutils \
      memcached \
      libmemcached-dev \
      libmemcachedutil2 \
      libmemcached11 \
      zlib1g

RUN apt-get -yqq install exiftool

RUN pecl install memcached \
    && pecl install apcu \
    && pecl install redis \
    && pecl install imagick

RUN docker-php-ext-configure exif \
  && docker-php-ext-configure gd \
  --with-jpeg \
  --with-freetype \
  --with-webp \
  --with-xpm \
  && docker-php-ext-install gd

RUN docker-php-ext-install exif \
  && docker-php-ext-install bcmath \
  && docker-php-ext-install bz2 \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install opcache \
  && docker-php-ext-install iconv \
  && docker-php-ext-install zip \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo_pgsql

RUN docker-php-ext-enable opcache \
  && docker-php-ext-enable memcached \
  && docker-php-ext-enable exif \
  && docker-php-ext-enable apcu \
  && docker-php-ext-enable redis
  
#RUN echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini
#RUN echo extension=apcu.so >> /usr/local/etc/php/conf.d/apcu.ini
#RUN echo "apc.enabled=1" >> /usr/local/etc/php/conf.d/apcu.ini
#RUN echo "apc.enable_cli=1" >> /usr/local/etc/php/conf.d/apcu.ini

RUN curl -s http://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer \
    && composer self-update

RUN php --ini
RUN php -m | grep -E "memcached|apcu"
RUN find /usr/local/etc/php -name "php.ini"

#COPY entrypoint.sh ./entrypoint.sh
#RUN chmod +x ./entrypoint.sh
#ENTRYPOINT ["/entrypoint.sh"]
#CMD ["php-fpm"]