FROM drupal:7-apache

ENV DRUPAL_VERSION 7

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .
COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN set -ex \
    && apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3

RUN mkdir /results

RUN curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer

RUN set -ex \
    && composer init --name "drupal/drupal" --type "drupal-core" -n \
	&& composer config version 7 \
	&& composer config minimum-stability dev \
	&& composer config prefer-stable true \
	&& composer require composer/installers \
	&& composer config repositories.pdo composer https://packages.drupal.org/7 \
	&& composer global require drush/drush:8.x \
	&& ln -s ~/.composer/vendor/bin/drush /usr/bin/drush

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
