# Base image: "php:8.3.0-cli" -> "php:8.3-cli" (oxirgi 8.3 patch relizi).
# Teg mavjudligini bu mashinadan TEKSHIRA OLMADIM (Docker/tarmoq yo'q).
FROM php:8.3-cli

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
# DIQQAT: bu image CI da faqat statik tahlil/testlar uchun ishlatiladi, shuning
# uchun dev-bog'liqliklar (phpunit, larastan) ATAYIN saqlanadi.
# Serverga deploy qilinadigan vendor/ BU IMAGE'dan OLINMAYDI - u .gitlab-ci.yml
# dagi "composer install --no-dev --optimize-autoloader" bilan quriladi.
RUN cd /tmp && composer install --no-scripts --no-interaction --prefer-dist
RUN mkdir -p /test && cp -a /tmp/vendor /test/

# From here we load our application's code in, therefore the previous docker
# "layer" thats been cached will be used if possible
WORKDIR /test
# .dockerignore (repo ildizida) .env, .git, vendor, node_modules, storage/app,
# tests va composer.phar ni build kontekstidan chiqaradi. Usiz ishchi kataloqdagi
# .env (jonli APP_KEY bilan) image qatlamiga pishib qolardi.
COPY --chown=www-data:www-data . /test

# .dockerignore storage/* va bootstrap/cache ni kontekstdan chiqargani uchun
# Laravel'ga kerak bo'ladigan yoziladigan kataloglarni qayta yaratamiz.
# Usiz phpunit/phpstan bootstrap bosqichida "directory not writable" bilan yiqiladi.
RUN mkdir -p /test/bootstrap/cache /test/storage/app/public /test/storage/framework/cache/data /test/storage/framework/sessions /test/storage/framework/testing /test/storage/framework/views /test/storage/logs && chown -R www-data:www-data /test/bootstrap/cache /test/storage && chmod -R ug+rwX /test/bootstrap/cache /test/storage

USER www-data
