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
  $commands[] = 'composer config repositories.' . $args['project'] . ' \'' . $options . '\'';
}

// Project version
if (!empty ($args['version'])) {
  $commands[] = 'composer require ' . $args['require_project'] . ':' . $args['version'];
}
else  {
  $commands[] = 'composer require ' . $args['require_project'];
}
run_commands($commands);

// Also install the require-dev stuff from the project being tested
// This is a workaround to the fact that Composer only reads `require-dev` from root composer.json.
$json_files = exec('find ~/.composer -name *' . $args['project'] . '.json');
if (!empty($json_files)) {
  foreach (explode(PHP_EOL, $json_files) as $json_file) {
    if (file_exists($json_file)) {
      $json_file_contents = file_get_contents($json_file);
      $config = json_decode($json_file_contents);
      $project = 'drupal/' . $args['project'];
      $version = $args['version'];
      $tmp = $config->packages->{$project};
      if (isset($tmp->{$version})) {
        if (isset($tmp->{$version}->{'require-dev'})) {
          $require_dev = $tmp->{$version}->{'require-dev'};
          $packages = array(); 
          foreach ($require_dev as $item => $ver) {
            $packages[] = $item . ':*';
          }
          // Now install all dev dependencies.
          $commands[] = 'echo Instaling require-dev packages of ' . $args['project'];
          // @todo use `sudo -u www-data /usr/local/bin/composer`
          $commands[] = 'composer require --no-interaction ' . implode(' ', $packages) . ' --prefer-stable --no-progress --no-suggest';
          run_commands($commands);
        }
      }
    }
  } 
}

// Apply patches.
if (!empty($args['patches'])) {
  $patches = explode(',', $args['patches']);
  foreach ($patches as  $patch) {
    $commands[] = 'curl -L -o modules/' . $args['project'] . '/'. basename($patch) . ' ' . trim($patch);
    $commands[] = 'cd modules/' . $args['project'] . '; patch -p1 < ' . basename($patch);
  }
  run_commands($commands);
}

// Run tests.
$code = run_tests($args);

// Keep results.
$commands[] = 'cp -a /var/www/html/sites/default/files/simpletest /results';
run_commands($commands);

exit($code);

/**
 * Run commands
 */
function run_commands(&$commands) {
  $code = 0;
  foreach ($commands as $command) {
    echo '[RUN] ' . $command . PHP_EOL;
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
    'patch' =>  '',
    'patches' =>  '',
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
