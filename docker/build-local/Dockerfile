FROM local/app-base:latest

MAINTAINER IWF Web Solutions Developer <developer@iwf.ch>

# Environment variables (with defaults)
ENV RUNTIME_ENVIRONMENT=local

USER root

# install xdebug
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=\"/tmp/xdebug.log\"" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# install pcov for local code coverage
RUN pecl install pcov && docker-php-ext-enable pcov

# install yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add - \
    && curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash - \
    && echo "deb http://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get -y install nodejs yarn

RUN composer self-update --2 \
    && mkdir -p /var/www/.cache /var/www/.composer/cache \
    && chown -R www-data:www-data /var/www/.cache /var/www/.composer/cache

# change the user id of www-data to the vagrant user to not run into serious permission issues
RUN usermod -u 1000 www-data && find / -user 33 ! -path "/proc/*" -exec chown -h www-data {} \; || true

WORKDIR /app

USER www-data

