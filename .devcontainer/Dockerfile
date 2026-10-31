FROM node:24-bookworm AS node
FROM php:8.4-cli-bookworm
RUN apt-get update && apt-get install -y --no-install-recommends git unzip zip ca-certificates libzip-dev libonig-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libicu-dev libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j2 pdo_mysql mbstring zip exif pcntl bcmath gd intl curl \
    && rm -rf /var/lib/apt/lists/*
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx \
    && printf 'memory_limit=512M\nmax_execution_time=600\nupload_max_filesize=32M\npost_max_size=40M\n' > /usr/local/etc/php/conf.d/trial.ini
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /var/www
