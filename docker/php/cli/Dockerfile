FROM php:8.3.0-cli

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

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

