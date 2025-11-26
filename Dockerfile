ARG PHP_VERSION=8.4
ARG COMPOSER_VERSION=latest
ARG APP_ENV

FROM composer:${COMPOSER_VERSION} AS vendor

FROM php:${PHP_VERSION}-cli-alpine

LABEL maintainer="Jorge Thomas <info@notakrista.com>"
LABEL org.opencontainers.image.title="Fillakit"
LABEL org.opencontainers.image.description="Laravel Starter Kit with Filament"
LABEL org.opencontainers.image.source=https://github.com/akrista/fillakit
LABEL org.opencontainers.image.licenses=MIT

ARG USER_ID=1000
ARG GROUP_ID=1000
ARG TZ=UTC
ARG APP_ENV

ENV TERM=xterm-color \
    WITH_HORIZON=false \
    WITH_SCHEDULER=false \
    WITH_WORKER=false \
    WORKER_COMMAND="php artisan queue:work" \
    OCTANE_SERVER=swoole \
    TZ=${TZ} \
    USER=laravel \
    APP_ENV=${APP_ENV} \
    ROOT=/var/www/html \
    COMPOSER_FUND=0 \
    COMPOSER_MAX_PARALLEL_HTTP=48 \
    NODE_PACKAGE_URL=https://unofficial-builds.nodejs.org/download/release/v24.11.0/node-v24.11.0-linux-x64-musl.tar.gz

WORKDIR ${ROOT}

SHELL ["/bin/sh", "-eou", "pipefail", "-c"]

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk update; \
    apk upgrade; \
    apk add --no-cache \
    supervisor \
    curl \
    # wget \
    # vim \
    # tzdata \
    # git \
    # ncdu \
    # procps \
    # unzip \
    # ca-certificates \
    # libsodium-dev \
    # brotli \
    && curl -fsSL ${NODE_PACKAGE_URL} -o /tmp/node.tar.gz \
    && tar -xzf /tmp/node.tar.gz -C /usr/local --strip-components=1 \
    && rm -f /tmp/node.tar.gz \
    # Install PHP extensions
    && install-php-extensions \
    openswoole \
    apcu \
    pcntl \
    intl \
    zip \
    exif \
    # bz2 \
    # mbstring \
    # bcmath \
    # uv \
    # vips \
    # gd \
    # rdkafka \
    # ffi \
    # igbinary \
    # swoole \
    # redis \
    # pgsql \
    # pdo_pgsql \
    # pdo_mysql \
    # pdo_sqlsrv \
    # sockets \
    # opcache \
    # ldap \
    && docker-php-source delete \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

RUN arch="$(apk --print-arch)" \
    && case "$arch" in \
    armhf) _cronic_fname='supercronic-linux-arm' ;; \
    aarch64) _cronic_fname='supercronic-linux-arm64' ;; \
    x86_64) _cronic_fname='supercronic-linux-amd64' ;; \
    x86) _cronic_fname='supercronic-linux-386' ;; \
    *) echo >&2 "error: unsupported architecture: $arch"; exit 1 ;; \
    esac \
    && wget -q "https://github.com/aptible/supercronic/releases/download/v0.2.38/${_cronic_fname}" \
    -O /usr/bin/supercronic \
    && chmod +x /usr/bin/supercronic \
    && mkdir -p /etc/supercronic \
    && echo "*/1 * * * * php ${ROOT}/artisan schedule:run --no-interaction" > /etc/supercronic/laravel

RUN addgroup -g ${GROUP_ID} ${USER} \
    && adduser -D -G ${USER} -u ${USER_ID} -s /bin/sh ${USER}
RUN mkdir -p /var/log/supervisor /var/run/supervisor \
    && chown -R ${USER}:${USER} ${ROOT} /var/log /var/run \
    && chmod -R a+rw ${ROOT} /var/log /var/run
RUN cp ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini

COPY --link --chown=${WWWUSER}:${WWWUSER} --from=vendor /usr/bin/composer /usr/bin/composer

COPY --link --chown=${WWWUSER}:${WWWUSER} docker/supervisord.conf /etc/
COPY --link --chown=${WWWUSER}:${WWWUSER} docker/supervisord.swoole.conf /etc/supervisor/conf.d/
COPY --link --chown=${WWWUSER}:${WWWUSER} docker/php.ini ${PHP_INI_DIR}/conf.d/99-octane.ini
COPY --link --chown=${WWWUSER}:${WWWUSER} docker/start-container /usr/local/bin/start-container
COPY --link --chown=${WWWUSER}:${WWWUSER} docker/healthcheck /usr/local/bin/healthcheck

COPY --link composer.* ./

RUN composer i \
    --no-dev \
    --no-interaction \
    --no-autoloader \
    --no-ansi \
    --no-scripts \
    --no-progress \
    --audit

COPY --link package.json package-lock.json* ./

RUN npm ci

COPY --link . .

RUN mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache \
    && chown -R ${USER_ID}:${GROUP_ID} ${ROOT} \
    && chmod +x /usr/local/bin/start-container /usr/local/bin/healthcheck

RUN composer dump-autoload \
    --optimize \
    --apcu \
    --classmap-authoritative \
    --no-interaction \
    --no-ansi \
    --no-dev \
    && composer clear-cache

RUN npm run build

USER ${USER}

EXPOSE 8000
EXPOSE 8080

ENTRYPOINT ["start-container"]

HEALTHCHECK --start-period=5s --interval=1s --timeout=3s --retries=10 CMD healthcheck || exit 1