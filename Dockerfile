FROM php:8.4-fpm
COPY . /usr/src/app
WORKDIR /srv

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --chown=noroot:noroot . /var/www/app

EXPOSE 9000

ENV PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/srv/bin:/opt/bin

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

RUN apt-get update

RUN cd /usr/src/app \
    && composer --profile --no-scripts install \
    && docker-php-ext-install pdo_mysql