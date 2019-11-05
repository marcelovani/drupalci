FROM drupal:8-apache

ENV DRUPAL_VERSION 9
ENV APACHE_LOG_DIR /var/log/apache2

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .
COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN docker-php-ext-install bcmath

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
	&& cd /var/www \
	&& sudo -u www-data composer create-project -s dev drupal/recommended-project:^8 --no-interaction --no-install drupal \
	&& rm -rf html \
	&& ln -s drupal/web html \
	&& cd drupal \
	&& sudo -u www-data composer require drupal/core-recommended:9.0.x-dev drupal/core-dev:9.0.x-dev --update-with-dependencies

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
