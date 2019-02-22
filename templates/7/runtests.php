<?php
function run_tests($args) {
  $commands = array();
  $commands[] = 'drush si minimal --db-url=sqlite://sites/default/files/.ht.sqlite -y';
  $commands[] = 'chown -R www-data:www-data sites/default';
  $commands[] = 'drush en -y simpletest';
  $commands[] = 'drush en -y ' . $args['project'];
  $commands[] = 'sudo -u www-data php scripts/run-tests.sh ' .
                 '--php /usr/local/bin/php ' .
                 '--color ' .
                 '--concurrency "31" ' .
                 '--verbose ' .
                 '--directory "modules/' . $args['project'] . '"';
  // Keep results.
  $commands[] = 'cp -a /var/www/html/sites/default/files/simpletest /results';

  return run_commands($commands);
}
