# Base image: oldin "php:8.3.0-fpm" edi - bu 8.3 seriyasining ENG BIRINCHI relizi,
# ya'ni undan keyingi barcha PHP xavfsizlik patchlari yo'q.
# "8.3-fpm" - PHP 8.3 ning oxirgi patch relizi (suzuvchi patch tegi).
# ESLATMA: bu mashinada tarmoq/Docker yo'q, teg mavjudligini TEKSHIRA OLMADIM.
FROM php:8.3-fpm

RUN apt-get update && apt-get upgrade -y && apt-get install -y  \
    libmcrypt-dev  \
    libzip-dev  \
    zip  \
    libxml2-dev  \
    libpq-dev  \
    libpng-dev  \
    libjpeg-dev  \
    libfreetype6-dev  \
    libxslt-dev  \
    libgcrypt-dev  \
    wget  \
    unzip \
    procps \
    libyaml-dev \
    imagemagick \
    libmagickwand-dev \
    libfcgi-bin \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-source extract \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && pecl install yaml-2.2.3 \
    && docker-php-ext-enable yaml \
    && pecl install mcrypt-1.0.7 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install  \
    sockets \
    gd \
    dom \
    pcntl \
    pdo_pgsql \
    pgsql \
    zip \
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# B1: pool.d/www.conf-local dagi slowlog uchun katalog. Ilgari hech qayerda
# yaratilmagandi, shuning uchun FPM ishga tushishda log yozolmasdi.
RUN mkdir -p /var/log/php-fpm     && chown -R www-data:www-data /var/log/php-fpm

# 9000 faqat docker tarmog'i ichida. Hostga chiqarilmaydi (compose'da ports yo'q).
EXPOSE 9000
