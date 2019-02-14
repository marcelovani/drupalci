FROM drupal:8-fpm-alpine

RUN set -ex \
	&& apk add sudo git \
	&& curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& curl -L -o drush https://github.com/drush-ops/drush-launcher/releases/download/0.4.2/drush.phar && chmod +x drush && sudo mv drush /usr/bin/drush \
	&& cd /var/www/html \
	&& rm composer.lock \
	&& composer require drush/drush \
	&& composer install

COPY runtests.sh /var/www/html

RUN sh /var/www/html/runtests.sh standard captcha_keypad broken_test-dev https://github.com/marcelovani/captcha_keypad.git
