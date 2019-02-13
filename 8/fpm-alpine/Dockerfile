FROM drupal:8-fpm-alpine

RUN set -ex \
	&& apk add sudo git \
	&& curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& curl -L -o drush https://github.com/drush-ops/drush-launcher/releases/download/0.4.2/drush.phar && chmod +x drush && sudo mv drush /usr/bin/drush \
	&& cd /var/www/html && rm composer.lock && composer install && php core/scripts/drupal install minimal -vvv
