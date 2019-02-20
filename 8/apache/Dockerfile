FROM drupal:8-apache

COPY runtests.php /var/www/html
COPY php-overrides.ini /usr/local/etc/php/conf.d

RUN set -ex \
    && apt-get update \
    && apt-get install -y \
        sudo \
        apt-utils \
        unzip \
        git

RUN sudo -u www-data echo 'Installing composer' \
	&& curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& cd /var/www/html \
	&& rm composer.lock \
	&& composer install

RUN php runtests.php --profile standard --project captcha_keypad --version 8.x-1.x-dev --vcs https://github.com/marcelovani/captcha_keypad.git
