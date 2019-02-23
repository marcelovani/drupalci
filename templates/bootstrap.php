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
// Also because if the module relies on the drupal logic to require dependencies via yaml file, see
// https://www.drupal.org/docs/8/creating-custom-modules/let-drupal-8-know-about-your-module-with-an-infoyml-file
$json_files = exec('find ~/.composer -name *' . $args['project'] . '.json');
if (!empty($json_files)) {
  foreach (explode(PHP_EOL, $json_files) as $json_file) {
    if (file_exists($json_file)) {
      $json_file_contents = file_get_contents($json_file);
      $config = json_decode($json_file_contents);
      $project = 'drupal/' . $args['project'];
      $dev = 'dev-1.x'; //@todo this cannot be hard coded, needs to be resolved as the top parent of th branch being tested
      if (isset($config->packages->{$project}->{$dev})) {
        $list = array(); 
        foreach (array('require', 'require-dev') as $r) {
          if (isset($config->packages->{$project}->{$dev}->{$r})) {
            $packages = $config->packages->{$project}->{$dev}->{$r};
            foreach ($packages as $item => $ver) {
              $list[] = $item . ':*';
            }
          }
        }
        if (!empty($list)) {
          // Now install all dev dependencies.
          $commands[] = 'echo Instaling required packages of ' . $args['project'];
          // @todo use `sudo -u www-data /usr/local/bin/composer`
          $commands[] = 'composer require --no-interaction ' . implode(' ', $list) . ' --prefer-stable --no-progress --no-suggest';
        }
      }
    }
  }
  run_commands($commands);
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
