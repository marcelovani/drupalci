FROM drupal:7-php7.4-apache

ENV DRUPAL_VERSION 7

RUN docker-php-ext-install bcmath > /dev/null

COPY php-overrides.ini /usr/local/etc/php/conf.d
COPY composer.json .

RUN chown -R www-data:www-data /var/www \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && mkdir /artifacts

RUN set -ex \
    && apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3

#RUN set -ex \
#    && echo Applying patch to core since composer cannot do it \
#    && curl -L -o sqlite.patch https://www.drupal.org/files/issues/2020-10-03/1713332-97.patch \
#    && patch -p1 < sqlite.patch

RUN set -ex \
    && curl -L -o /usr/bin/drush https://github.com/drush-ops/drush/releases/download/8.4.5/drush.phar \
    && chmod +x /usr/bin/drush

RUN set -ex \
    && sudo -u www-data curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/local/bin --filename=composer \
    && composer --version

RUN a2enmod headers; service apache2 restart

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
