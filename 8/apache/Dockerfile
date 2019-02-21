FROM drupal:8-apache

COPY runtests.php .
COPY entrypoint.sh .
COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN set -ex \
    && apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git

RUN curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& rm composer.lock \
	&& composer install \
	&& mkdir /verbose

EXPOSE 80
ENTRYPOINT ["./entrypoint.sh"]
