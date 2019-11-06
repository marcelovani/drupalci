<?php
function run_tests($args) {
  $commands = array();
  $commands[] = 'sudo -u www-data drush si minimal --db-url=sqlite://sites/default/files/.ht.sqlite -y';
  $commands[] = 'drush en -y simpletest';
  $commands[] = 'drush en -y ' . $args['project'];
  $commands[] = 'sudo -u www-data php scripts/run-tests.sh ' .
                 '--php /usr/local/bin/php ' .
                 '--color ' .
                 '--concurrency "31" ' .
                 '--verbose ' .
                 '--directory "modules/' . $args['project'] . '"';

  return run_commands($commands);
}
