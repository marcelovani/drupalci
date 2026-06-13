<?php
function run_tests($args) {
  $commands = array();
  // D11.4+ deprecated `php core/scripts/drupal`; use vendor/bin/dr so Composer
  // sets _composer_autoload_path correctly before the script runs.
  $commands[] = 'cd /var/www/html && sudo -u www-data vendor/bin/dr install ' . $args['profile'];
  $commands[] = 'cd /var/www/html/web && sudo -u www-data php core/scripts/run-tests.sh ' .
    '--php /usr/local/bin/php ' .
    '--keep-results ' .
    '--color ' .
    '--suppress-deprecations ' .
    '--types "Simpletest,PHPUnit-Unit,PHPUnit-Kernel,PHPUnit-Functional" ' .
    '--concurrency "32" ' .
    '--repeat "1" ' .
    '--sqlite sites/default/files/.ht.sqlite ' .
    '--url http://localhost ' .
    '--verbose ' .
    '--directory "modules/contrib/' . $args['project'] . '"';

  return run_commands($commands);
}
