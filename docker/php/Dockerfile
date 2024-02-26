FROM php:7.2-fpm-alpine
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY ./php.ini /usr/local/etc/php/php.ini
WORKDIR /root/src
RUN apk add --update --no-cache tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo "Asia/Tokyo" > /etc/timezone && \
    apk del tzdata && \
    rm -rf /var/cache/apk/*
RUN apk add --update --no-cache \
        git \
    && rm -rf /var/cache/apk/*
RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
    && docker-php-ext-install redis
EXPOSE 9000
