<?php
function run_tests($args) {
  $commands = $args['commands'];
  $commands[] = 'sudo -u www-data php core/scripts/drupal install ' . $args['profile'];
  $commands[] = 'sudo -u www-data php core/scripts/run-tests.sh ' .
                   '--php /usr/local/bin/php ' .
                   '--keep-results ' .
                   '--color ' .
                   '--concurrency "31" ' .
                   '--sqlite sites/default/files/.ht.sqlite ' .
                   '--verbose ' .
                   '--directory "modules/contrib/' . $args['project'] . '"';
  // Keep results.
  $commands[] = 'cp -a /var/www/html/sites/default/files/simpletest /results';

  return run_commands($commands);
}
