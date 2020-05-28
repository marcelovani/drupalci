<?php
require_once('runtests.php');

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

$commands = array();

// Adds VCS
if (!empty ($args['vcs'])) {
  $options = array(
    'type' => 'vcs',
    'url' => $args['vcs'],
  );

  if (strpos($args['vcs'], 'https') !== false) {
    $options['no-api'] = true;
  }
  $options = json_encode($options, JSON_UNESCAPED_SLASHES);

  $commands[] = 'sudo -u www-data composer config repositories.' . $args['project'] . ' \'' . $options . '\'';
}

// Composer require.
$composer_require = [];

// Project version
if (!empty ($args['version'])) {
  $composer_require[] = $args['require_project'] . ':' . $args['version'];
}
else  {
  $composer_require[] = $args['require_project'];
}

// Require dependencies
if (!empty ($args['dependencies'])) {
  $dependencies = str_replace(',', ' ', $args['dependencies']);
  $dependencies = explode(' ', $dependencies);
  $dependencies = array_filter($dependencies);
  $composer_require = array_merge($composer_require, $dependencies);
}

$options = ' --prefer-source --prefer-stable --no-progress --no-suggest --no-interaction';
$commands[] = 'sudo -u www-data COMPOSER_MEMORY_LIMIT=-1 composer require ' . implode(' ', $composer_require) . $options;
run_commands($commands);

// Apply patches.
if (!empty($args['patches'])) {
  $patches = explode(',', $args['patches']);
  foreach ($patches as  $patch) {
    $commands[] = 'curl -L -o modules/' . $args['project'] . '/'. basename($patch) . ' ' . trim($patch);
    $commands[] = 'cd modules/' . $args['project'] . '; patch -p1 < ' . basename($patch);
  }
}

// Run tests.
$code = run_tests($args);

// Keep results.
if (getenv('DRUPAL_VERSION') == '7') {
  $commands[] = 'cp -a /var/www/html/sites/default/files/simpletest /artifacts';
}
else {
  $commands[] = 'cp -a /var/www/html/web/sites/default/files/simpletest /artifacts';
}
$commands[] = 'cp -a /var/www/html/composer.json /artifacts';

run_commands($commands);

exit($code);

/**
 * Run commands
 */
function run_commands(&$commands) {
  $code = 0;
  foreach ($commands as $command) {
    echo "\e[0;1;33m$command \e[0m" . PHP_EOL;
    passthru($command, $err);
    if ($err != 0) {
      $code = 1;
    }
  }
  $commands = array();

  return $code;
}

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
    'patches' =>  '',
    'dependencies' =>  '',
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
        echo "\e[0;0;31m[ERROR] Unknown argument $arg \e[0m" . PHP_EOL;
        exit(1);
      }
    }
  }

  return [$args];
}
