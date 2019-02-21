FROM drupal:8-fpm-alpine

COPY runtests.php .
COPY entrypoint.sh .
COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN set -ex \
    && apk add sudo git

RUN curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& rm composer.lock \
	&& composer install \
	&& mkdir /verbose

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
