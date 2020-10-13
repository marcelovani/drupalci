<?php
function run_tests($args) {
  $commands = array();
  $commands[] = 'drush --version';
  $commands[] = 'sudo -u www-data drush si minimal --db-url=sqlite://sites/default/files/.ht.sqlite -y install_configure_form.update_status_module="array(FALSE,FALSE)"';
  $commands[] = 'drush en -y simpletest';
  $commands[] = 'drush en -y ' . $args['project'];
//  $commands[] = 'sudo -u www-data php scripts/run-tests.sh ' .
//                 '--php /usr/local/bin/php ' .
//                 '--color ' .
//                 '--concurrency "31" ' .
//                 '--verbose ' .
//                 '--directory "modules/' . $args['project'] . '"';

  $commands[] = 'sudo -u www-data php scripts/run-tests.sh ' .
    '--php /usr/local/bin/php ' .
    //'--keep-results ' .
    '--color ' .
//    '--suppress-deprecations ' .
//    '--types "Simpletest,PHPUnit-Unit,PHPUnit-Kernel,PHPUnit-Functional" ' .
    '--concurrency "32" ' .
//    '--repeat "1" ' .
//    '--sqlite sites/default/files/.ht.sqlite ' .
    '--verbose ' .
    '--directory "modules/' . $args['project'] . '"';

  return run_commands($commands);
}
