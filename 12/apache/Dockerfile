# No official drupal:12-* image exists yet on Docker Hub.
# Drupal 12 (dev-main) requires PHP >= 8.5.
# Using drupal:11-php8.5-apache as a temporary base (PHP 8.5, same Apache setup).
# Switch this line to drupal:12-phpX.Y-apache once official images are published.
FROM drupal:11-php8.5-apache

ENV VERSION=1.0.0
ENV DRUPAL_VERSION=12
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
        sqlite3 > /dev/null

# drupal/recommended-project:^12 is not yet on Packagist — dev-main is the 12.x development branch.
RUN cd /var/www \
    && rm -rf html \
    && sudo -u www-data composer create-project --no-install --stability=dev drupal/recommended-project:dev-main html

RUN cd /var/www/html \
    && sudo -u www-data composer update drupal/core 'drupal/core-*' --with-all-dependencies \
    && sudo -u www-data composer require --with-all-dependencies --no-update \
       drupal/core-dev:dev-main \
       mikey179/vfsstream:^1.0 \
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
