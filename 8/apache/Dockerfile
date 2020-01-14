FROM drupal:8-apache

ENV DRUPAL_VERSION 8
ENV APACHE_LOG_DIR /var/log/apache2

RUN docker-php-ext-install bcmath > /dev/null

COPY php-overrides.ini /usr/local/etc/php/conf.d

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
    && sudo -u www-data curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
    && composer global require hirak/prestissimo:^0.3 \
    && rm composer.lock \
    && sudo -u www-data composer require drupal/core:^8 --dev

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
