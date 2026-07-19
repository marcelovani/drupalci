FROM drupal:11-php8.5-apache

ENV VERSION=1.0.0
ENV DRUPAL_VERSION=11
ENV APACHE_LOG_DIR=/var/log/messages
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web
ENV MINK_DRIVER_ARGS_WEBDRIVER='["chrome", {"browserName":"chrome","chromeOptions":{"args":["--disable-gpu","--headless"]}}, "http://host.docker.internal:9515"]'

RUN set -x

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY php-overrides.ini /usr/local/etc/php/conf.d
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN chown -R www-data:www-data /var/www
RUN mkdir /artifacts
RUN mkdir /var/log/messages

RUN apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3 \
        rsyslog > /dev/null

# Forward all syslog messages (this is how Drupal's core syslog module logs
# watchdog entries) to the container's stdout so they show up in
# `docker logs`/Docker Desktop's Logs tab instead of only /var/log/syslog.
RUN echo '*.*  /proc/1/fd/1' > /etc/rsyslog.d/60-docker.conf

RUN cd /var/www \
    && rm -rf html \
    && sudo -u www-data composer create-project --no-install drupal/recommended-project:^${DRUPAL_VERSION}@beta html

RUN cd /var/www/html \
    && sudo -u www-data composer update drupal/core 'drupal/core-*' --with-all-dependencies \
    && sudo -u www-data composer require --with-all-dependencies --no-update \
       drupal/core-dev:^${DRUPAL_VERSION} \
       mikey179/vfsstream:^1.0 \
       drush/drush:^13 \
    && sudo -u www-data composer update

RUN cd /var/www/html && ln -s web docroot

WORKDIR /var/www/html

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .
COPY sqlite.patch .

# Temporary fix to allow sqlite testing.
RUN set -ex \
   && cd web \
   && ls -al \
   && patch -p1 < ../sqlite.patch

RUN chown -R www-data:www-data /var/www

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD []
