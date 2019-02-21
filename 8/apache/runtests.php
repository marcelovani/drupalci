<?php

/**
 * @file
 * This script runs Drupal tests from command line.
 */

// Set defaults and get overrides.
list($args) = script_parse_args();

// Validate project
if (empty ($args['project'])) {
  die("You need to specify the Drupal project i.e. --project drupal/captcha_keypad");
}

// Prepend vendor if needed
if (strpos($args['project'], '/') !== FALSE) {
	$args['require_project'] = $args['project'];
}
else {
	$args['require_project'] = 'drupal/' . $args['project'];
}

// Prepare composer_commands
$composer_commands = [];

// Adds VCS
if (!empty ($args['vcs'])) {
  $options = array(
    'type' => 'vcs',
    'url' => $args['vcs'],
  );

  if (strpos($args['vcs'], 'https') !== false) {
    $options['no-api'] = true;
  }

  $composer_commands[] = 'composer config repositories.' . $args['project'] . ' \'' . json_encode($options, JSON_UNESCAPED_SLASHES) . '\'';
}

// Project version
if (!empty ($args['version'])) {
  $composer_commands[] = 'composer require ' . $args['require_project'] . ':' . $args['version'];
}
else  {
  $composer_commands[] = 'composer require ' . $args['require_project'];
}

// Collect all commands.
$commands = $composer_commands;
$commands[] = 'chmod -R 0777 sites/default';
$commands[] = 'sudo -u www-data php core/scripts/drupal install ' . $args['profile'];
$commands[] = 'sudo -u www-data php core/scripts/run-tests.sh --php /usr/local/bin/php --keep-results --color --concurrency "31" --sqlite sites/default/files/.ht.sqlite --verbose --directory "modules/contrib/' . $args['project'] . '"';
$commands[] = 'cp -a /var/www/html/sites/default/files/simpletest/verbose /';

// Run commands.
$code = 0;
foreach ($commands as $command) {
  echo '>>>' . $command . PHP_EOL;
  passthru($command, $err);
  if ($err != 0) {
    $code = 1;
  }
}
exit($code);

/**
 * Parse execution argument and ensure that all are valid.
 *
 * @return array
 *   The list of arguments.
 */
function script_parse_args() {
  // Set default values.
  $args = [
    'script' => '',
    'help' => FALSE,
    'profile' => 'minimal',
    'project' => '',
	  'version' => '',
	  'vcs' => '',
  ];

  // Override with set values.
  $args['script'] = basename(array_shift($_SERVER['argv']));

  while ($arg = array_shift($_SERVER['argv'])) {
    if (preg_match('/--(\S+)/', $arg, $matches)) {
      // Argument found.
      if (array_key_exists($matches[1], $args)) {
        // Argument found in list.
        $previous_arg = $matches[1];
        if (is_bool($args[$previous_arg])) {
          $args[$matches[1]] = TRUE;
        }
        elseif (is_array($args[$previous_arg])) {
          $value = array_shift($_SERVER['argv']);
          $args[$matches[1]] = array_map('trim', explode(',', $value));
        }
        else {
          $args[$matches[1]] = array_shift($_SERVER['argv']);
        }
      }
      else {
        // Argument not found in list.
        echo ("Unknown argument '$arg'.");
        exit(1);
      }
    }
  }

  return [$args];
}
