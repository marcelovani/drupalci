FROM drupal:7-apache

ENV DRUPAL_VERSION 7

RUN docker-php-ext-install bcmath > /dev/null

COPY php-overrides.ini /usr/local/etc/php/conf.d
COPY composer.json .

RUN chown -R www-data:www-data /var/www \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && mkdir /results

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
    && sudo -u www-data curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/local/bin --filename=composer \
    && sudo -u www-data composer install

EXPOSE 80
CMD ["apache2-foreground"]
