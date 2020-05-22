FROM marcelovani/drupalci:8-apache

ENV DRUPAL_VERSION 9

RUN set -ex \
    && cd /var/www && rm -rf html \
    && sudo -u www-data composer create-project --no-install -s dev drupal/recommended-project:^9 html

RUN set -ex \
    && sudo -u www-data composer update
    #&& sudo -u www-data composer require phpunit/phpunit

COPY sqlite.patch ./web
RUN set -ex \
    && echo Applying patch to core to make it work with Sqlite on Debian \
    && cd web && patch -p1 < sqlite.patch

COPY bootstrap.php .
COPY runtests.php .
COPY entrypoint.sh .

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
CMD []
