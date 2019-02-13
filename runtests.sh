#!/bin/sh
set -x

drupal_version=$1
require_module=$2

cd /var/www/html
ls
composer require drupal/${module}
cd web
php core/scripts/run-tests.sh --php /usr/local/bin/php --color --concurrency "31" --sqlite sites/default/files/.ht.sqlite --verbose --directory modules/contrib/${require_module}"
