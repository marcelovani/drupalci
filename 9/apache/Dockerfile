FROM marcelovani/drupalci:8-apache

ENV DRUPAL_VERSION 9

RUN cd /var/www \
    && rm -rf html \
    && sudo -u www-data composer create-project -s alpha --no-install drupal/recommended-project:^9 html

RUN cd /var/www/html \
    && sudo -u www-data composer require --with-all-dependencies --no-update \
       phpunit/phpunit:^8.0 \
       symfony/phpunit-bridge:^5.0 \
       behat/mink-selenium2-driver:^1.0 \
       behat/mink-browserkit-driver:^1.0 \
       behat/mink-goutte-driver:^1.0 \
       mikey179/vfsstream:^1.0 \
       drupal/coder:^8.0 \
    && sudo -u www-data composer update

COPY sqlite.patch ./web
RUN echo Applying patch to core to make it work with Sqlite on Debian \
    && cd web && patch -p1 < sqlite.patch

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .


EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD []
