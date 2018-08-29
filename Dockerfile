From php:7.2-alpine

ARG BUILD_DATE
ARG VCS_REF
ARG IMAGE_NAME

LABEL Maintainer="Zaher Ghaibeh <z@zah.me>" \
      Description="Lightweight container with PHP 7.2 based on Alpine Linux." \
      Date="28-08-2018"

ENV BACKUP_DIRECTORY ${BACKUP_DIRECTORY:-/app/backup}

COPY docker-entrypoint.sh /docker-entrypoint.sh

WORKDIR /app

ADD . /app

RUN apk update && apk upgrade && apk --no-cache add git \
    && docker-php-ext-install -j$(nproc) bcmath mbstring \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-progress --no-suggest --prefer-dist --no-dev --optimize-autoloader \
    && wget -O /usr/local/bin/dumb-init https://github.com/Yelp/dumb-init/releases/download/v1.2.0/dumb-init_1.2.0_amd64 \
    && chmod +x /usr/local/bin/dumb-init \
    && chmod +x simplecast

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["php","simplecast","download"]
