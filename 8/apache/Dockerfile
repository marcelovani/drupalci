FROM drupal:8-apache

LABEL com.circleci.preserve-entrypoint=true

ENV DRUPAL_VERSION 8
ENV APACHE_LOG_DIR /var/log/messages
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
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
    && sudo -u www-data composer require --no-update \
       phpunit/phpunit:^7.0 \
       symfony/phpunit-bridge:^5.0 \
       behat/mink-selenium2-driver:^1.0 \
       behat/mink-browserkit-driver:^1.0 \
       behat/mink-goutte-driver:^1.0 \
       mikey179/vfsstream:^1.0 \
       drupal/coder:^8.0 \
   && sudo -u www-data composer update

RUN cd /var/www/html && ln -s web docroot

WORKDIR /var/www/html

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

RUN chown -R www-data:www-data /var/www

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD []
