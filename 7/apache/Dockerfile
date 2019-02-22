FROM drupal:7-apache

ENV DRUPAL_VERSION 7

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .
COPY composer.json .
COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN mkdir /results

RUN set -ex \
    && apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3

RUN set -ex \
    && echo Applying patch to core since composer cannot do it \
    && curl -L -o sqlite.patch https://www.drupal.org/files/issues/2019-02-21/1713332-83.patch \
    && patch -p1 < sqlite.patch

RUN set -ex \
    && curl -L -o /usr/bin/drush https://github.com/drush-ops/drush/releases/download/8.1.18/drush.phar \
    && chmod +x /usr/bin/drush

RUN set -ex \
    && curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
    && composer install

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
