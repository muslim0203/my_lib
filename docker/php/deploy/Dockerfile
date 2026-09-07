FROM php:8.3.0-cli

ARG DEBIAN_FRONTEND=noninteractive

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

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

# use changes to composer.json to force Docker not to use the cache
# when we change our application's composer dependencies:
COPY composer.json composer.lock /tmp/
RUN cd /tmp && composer install --no-scripts
RUN mkdir -p /test && cp -a /tmp/vendor /test/

# From here we load our application's code in, therefore the previous docker
# "layer" thats been cached will be used if possible
WORKDIR /test
COPY --chown=www-data:www-data . /test

#RUN chown -R www-data:www-data /test
USER www-data
