FROM drupal:9-apache

ENV DRUPAL_VERSION 9
ENV APACHE_LOG_DIR /var/log/messages
ENV APACHE_DOCUMENT_ROOT /var/www/html/web
ENV MINK_DRIVER_ARGS_WEBDRIVER='["chrome", {"browserName":"chrome","chromeOptions":{"args":["--disable-gpu","--headless"]}}, "http://chromedriver:9515"]'

RUN set -x

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY php-overrides.ini /usr/local/etc/php/conf.d
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN chown -R www-data:www-data /var/www
RUN mkdir /artifacts
RUN mkdir /var/log/messages

RUN apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git \
        sqlite3 > /dev/null

RUN cd /var/www \
    && rm -rf html \
    && sudo -u www-data composer create-project -s alpha --no-install drupal/recommended-project:^9 html

RUN cd /var/www/html \
    && sudo -u www-data composer require --with-all-dependencies --no-update \
       phpunit/phpunit:^9.0 \
       symfony/phpunit-bridge:^5.0 \
       phpspec/prophecy-phpunit:^2 \
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
