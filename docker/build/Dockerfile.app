FROM local/app-base:latest

ARG ssh_prv_key
ARG ssh_pub_key

# switch to root
USER root

# Copy our release
COPY ./code.tar /tmp/

# project specific commands
RUN mkdir -p /app && \
    tar -xf /tmp/code.tar -C /app/  && \
    chown -R www-data:www-data /app

USER www-data

# activate and extend if you want to include cronjobs
# RUN (crontab -l ; echo "0 1 * * * /bin/bash /usr/local/bin/iwfsfconsole.sh --env=prod my:test:job >>/app/var/logs/cron.log 2>&1") | crontab -

# Run composer
WORKDIR /app
RUN mkdir -m 700 ~/.ssh && \
  touch -m 600 ~/.ssh/known_hosts && \
  ssh-keyscan git.iwf.io > ~/.ssh/known_hosts && \
  echo "$ssh_prv_key" > ~/.ssh/id_rsa && \
  echo "$ssh_pub_key" > ~/.ssh/id_rsa.pub && \
  chmod 600 ~/.ssh/id_rsa && \
  chmod 600 ~/.ssh/id_rsa.pub && \
  sudo composer self-update --2 && \
  composer install --optimize-autoloader && \
  rm -rf ~/.ssh

# Post commands
RUN php bin/console assets:install

# cleanup
RUN sudo apt-get clean && \
    sudo rm -rf /app/var/cache/* /tmp/* /var/tmp/* /usr/share/doc/* /var/lib/apt/lists/* /var/www/.composer .cache-loader
