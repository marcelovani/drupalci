FROM drupal:8-apache

COPY runtests.php /var/www/html

RUN set -ex \
    && apt-get update \
	&& apt-get install -y sudo \
	                      apt-utils \
	                      unzip \
	                      git \
	&& curl -L https://getcomposer.org/installer | php -d allow_url_fopen=on  -- --quiet --install-dir=/usr/bin --filename=composer \
	&& cd /var/www/html \
	&& rm composer.lock \
	&& composer install

#RUN /usr/local/bin/php /var/www/html/runtests.sh --profile standard --project captcha_keypad --version broken_test-dev --vcs https://github.com/marcelovani/captcha_keypad.git
#RUN /usr/local/bin/php /var/www/html/runtests.php --profile standard --project boxout --version 8.x-1.x-dev --vcs https://github.com/marcelovani/boxout.git
