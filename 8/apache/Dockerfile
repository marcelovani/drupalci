FROM drupal:8-apache

LABEL com.circleci.preserve-entrypoint=true

ENV DRUPAL_VERSION 8
ENV APACHE_LOG_DIR /var/log/messages

ENV APACHE_DOCUMENT_ROOT /var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN docker-php-ext-install bcmath > /dev/null
RUN docker-php-ext-install opcache

COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN chown -R www-data:www-data /var/www \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && mkdir /artifacts \
    && mkdir /var/log/messages

RUN set -ex \
    && apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3

RUN set -ex \
    && sudo -u www-data curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
    && composer global require hirak/prestissimo:^0.3

RUN set -ex \
    && cd /var/www && rm -rf html \
    && sudo -u www-data composer create-project -s alpha --no-install drupal/recommended-project:^8 html

RUN set -ex \
    && sudo -u www-data composer install \
    && sudo -u www-data composer require phpunit/phpunit:^6

RUN set -ex \
    && curl -L -o /usr/bin/drush https://github.com/drush-ops/drush-launcher/releases/download/0.4.2/drush.phar \
    && chmod +x /usr/bin/drush \
    && sudo curl -L -o /usr/local/bin/drupal https://drupalconsole.com/installer \
    && sudo chmod +x /usr/local/bin/drupal

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD []
