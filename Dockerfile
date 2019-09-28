FROM webdevops/php-nginx-dev:7.2

# intl
RUN set -x \
    && apt-get update \
	&& apt-get install -y libicu-dev libldap2-dev \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
	&& docker-php-ext-install intl ldap bcmath

# nodejs
ENV NODE_VERSION 10.16.2
RUN ARCH= && dpkgArch="$(dpkg --print-architecture)" \
  && case "${dpkgArch##*-}" in \
    amd64) ARCH='x64';; \
    ppc64el) ARCH='ppc64le';; \
    s390x) ARCH='s390x';; \
    arm64) ARCH='arm64';; \
    armhf) ARCH='armv7l';; \
    i386) ARCH='x86';; \
    *) echo "unsupported architecture"; exit 1 ;; \
  esac \
  # gpg keys listed at https://github.com/nodejs/node#release-keys
  && set -x \
  && curl -fsSLO --compressed "https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-$ARCH.tar.xz" \
  && tar -xJf "node-v$NODE_VERSION-linux-$ARCH.tar.xz" -C /usr/local --strip-components=1 --no-same-owner \
  && ln -s /usr/local/bin/node /usr/local/bin/nodejs


##DEPENDENCY HANDLING
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin/ --filename=composer

ARG GITHUB_OAUTH_TOKEN
ARG COMPOSER_HOME
ARG CURRENT_USER
ARG APP_ROOT
ARG GITLAB_AUTH_TOKEN_USR
ARG GITLAB_AUTH_TOKEN_PSW
WORKDIR $APP_ROOT

COPY composer_auth.php ./

RUN mkdir -pv $COMPOSER_HOME/cache
RUN chown -R application:application $COMPOSER_HOME/cache

RUN mkdir -pv $APP_ROOT/var/cache $APP_ROOT/var/sessions
RUN php composer_auth.php $GITHUB_OAUTH_TOKEN $GITLAB_AUTH_TOKEN_USR $GITLAB_AUTH_TOKEN_PSW $COMPOSER_HOME

COPY composer.json ./
COPY composer.lock ./

## Add parallel downloader for composer
RUN composer global require hirak/prestissimo deployer/deployer deployer/recipes symfony/dotenv
## composer install to warmup cache
RUN php -dmemory_limit=-1 $(which composer) install --no-scripts --no-autoloader --prefer-dist

RUN chmod -R 777 $APP_ROOT/var/cache
RUN chown -R application:application $COMPOSER_HOME $APP_ROOT

##COPY
COPY .docker/conf/nginx/vhost.conf /opt/docker/etc/nginx/vhost.conf
COPY .docker/conf/nginx/oro-location.conf /opt/docker/etc/nginx/vhost.common.d/10-location-root.conf
COPY .docker/conf/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY .docker/conf/supervisor/consumer.conf /opt/docker/etc/supervisor.d/consumer.conf
COPY .docker/conf/opt/docker/provision/entrypoint.d/05-permissions.sh /opt/docker/provision/entrypoint.d/05-permissions.sh