# drupal:7 is EOL (PHP 7.4/Buster). Pin to linux/amd64 to avoid PHP 7.4 ARM64
# segfaults under emulation. Runs via Rosetta on Apple Silicon; native on CI.
FROM --platform=linux/amd64 drupal:7-php7.4-apache

ENV DRUPAL_VERSION=7

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

RUN set -ex \
    && curl -L -o /usr/bin/drush https://github.com/drush-ops/drush/releases/download/8.5.0/drush.phar \
    && chmod +x /usr/bin/drush

RUN sudo curl -L https://getcomposer.org/composer-2.phar --output ~/composer-2.phar \
    && sudo chmod +x ~/composer-2.phar \
    && sudo mv ~/composer-2.phar /usr/local/bin/composer \
    && composer --version

RUN a2enmod headers; service apache2 restart

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
