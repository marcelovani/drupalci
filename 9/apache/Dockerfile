FROM drupal:8-apache

ENV DRUPAL_VERSION 9
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
        git

RUN set -ex \
    && sudo -u www-data curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& cd /var/www && sudo -u www-data composer create-project -s dev drupal/recommended-project:^8 --no-interaction --no-install drupal \
	&& cd drupal   && sudo -u www-data composer require drupal/core-recommended:9.0.x-dev drupal/core-dev:9.0.x-dev --update-with-dependencies \
	&& cd /var/www && rm -rf html && sudo -u www-data ln -s drupal/web html

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
