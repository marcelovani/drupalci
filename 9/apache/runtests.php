<?php
function run_tests($args) {
  $commands = array();
  $commands[] = 'cd /var/www/html/web && sudo -u www-data php core/scripts/drupal install ' . $args['profile'];
  $commands[] = 'cd /var/www/html/web && sudo -u www-data php core/scripts/run-tests.sh ' .
    '--php /usr/local/bin/php ' .
    '--keep-results ' .
    '--color ' .
    '--suppress-deprecations ' .
    '--types "Simpletest,PHPUnit-Unit,PHPUnit-Kernel,PHPUnit-Functional" ' .
    '--concurrency "32" ' .
    '--repeat "1" ' .
    '--sqlite sites/default/files/.ht.sqlite ' .
    '--verbose ' .
    '--directory "modules/contrib/' . $args['project'] . '"';

  return run_commands($commands);
}
