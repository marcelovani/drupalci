# drupal:8 is EOL (PHP 7.4/Buster). Pin to linux/amd64 to avoid PHP 7.4 ARM64
# segfaults under emulation. Runs via Rosetta on Apple Silicon; native on CI.
FROM --platform=linux/amd64 drupal:8-apache

LABEL com.circleci.preserve-entrypoint=true

ENV DRUPAL_VERSION=8
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

RUN docker-php-ext-install bcmath > /dev/null
RUN docker-php-ext-install opcache > /dev/null
# Debian Buster is EOL; redirect to the archive mirror.
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list \
    && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
    && sed -i '/buster-updates/d' /etc/apt/sources.list

RUN apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3 > /dev/null

#RUN sudo -u www-data curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer
RUN sudo curl -L https://getcomposer.org/composer-2.phar --output ~/composer-2.phar \
    && sudo chmod +x ~/composer-2.phar \
    && sudo mv ~/composer-2.phar /usr/local/bin/composer \
    && composer --version

RUN sudo curl -L -o /usr/bin/drush https://github.com/drush-ops/drush-launcher/releases/download/0.4.2/drush.phar \
    && sudo chmod +x /usr/bin/drush

RUN sudo curl -L -o /usr/local/bin/drupal https://drupalconsole.com/installer \
    && sudo chmod +x /usr/local/bin/drupal

RUN set +x

RUN cd /var/www \
    && rm -rf html \
    && sudo -u www-data composer --no-cache --profile create-project -s alpha --no-install drupal/recommended-project:^8 html

RUN cd /var/www/html \
    && sudo -u www-data composer config --no-interaction policy.advisories.block false \
    && sudo -u www-data composer config --no-plugins allow-plugins.composer/installers true \
    && sudo -u www-data composer config --no-plugins allow-plugins.dealerdirect/phpcodesniffer-composer-installer true \
    && sudo -u www-data composer config --no-plugins allow-plugins.drupal/core-composer-scaffold true \
    && sudo -u www-data composer config --no-plugins allow-plugins.drupal/core-project-message true \
    && sudo -u www-data COMPOSER_MEMORY_LIMIT=-1 composer require --no-update \
       drupal/core-dev:^${DRUPAL_VERSION} \
       mikey179/vfsstream:^1.0 \
    && sudo -u www-data COMPOSER_MEMORY_LIMIT=-1 composer update --with-all-dependencies

RUN cd /var/www/html && ln -s web docroot

WORKDIR /var/www/html

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

RUN chown -R www-data:www-data /var/www

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD []
