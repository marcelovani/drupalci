# DrupalCi

[![Docker build auto](https://img.shields.io/docker/automated/dennisinteractive/drupalci.svg)](https://hub.docker.com/r/dennisinteractive/drupalci)
[![Docker pulls](https://img.shields.io/docker/pulls/dennisinteractive/drupalci.svg)](https://hub.docker.com/r/dennisinteractive/drupalci)

Runs Drupal webtests and php unit tests using Docker containers.
This can be used with Git webhooks but its not ready yet.

## Usage examples
The parameters for the runtests.php script are:
* --project      Project or module name
* --version      Project version or branch name [optional]. 
                 The format is the same as used in [Composer](https://getcomposer.org/doc/04-schema.md#version)
* --vcs          Fork url [optional]
* --profile      Drupal install profile [optional]
* --patches      Specifies a list of patches to be applied. [See example below](#Patches).
* --dependencies List of test dependencies [optional] i.e. "drupal/link:* drupal/email:^1.0"

### Drupal 8
Run tests from the a released version of Captcha Keypad module

```bash
docker run --name drupalci --rm dennisinteractive/drupalci:8-apache \
       --project captcha_keypad \
       --version ^1.0.0
```

### Drupal 7

```bash
docker run --name drupalci --rm dennisinteractive/drupalci:7-apache \
       --project captcha_keypad \
       --version ^1.0.0
```

### Forks and branches
To run tests from the a forked branch you can use --version with the branch.
See [Non feature branches](https://getcomposer.org/doc/04-schema.md#non-feature-branches).
You can also specify the repository using --vcs.

```bash
docker run --name drupalci --rm dennisinteractive/drupalci:8-apache \
       --project captcha_keypad \
       --version dev-8.x-1.x \
       --vcs https://github.com/dennisinteractive/captcha_keypad.git
```

### Patches
You can provide a list of patches to be applied to the project.

```bash
docker run --name drupalci --rm dennisinteractive/drupalci:7-apache \
       --project amp \
       --version dev-1.x \
       --patches https://www.drupal.org/files/issues/2019-02-11/amp-initial-page-load-3031306-18.patch
```
 
For multiple patches, each Url needs to be separated by comma.

```bash
docker run --name drupalci --rm dennisinteractive/drupalci:7-apache \
       --project captcha_keypad \
       --version dev-1.x \
       --patches "https://www.example.com/fix-1.patch, https://www.example.com/fix-2.patch"
```

### Dependencies
Used to install test dependencies or any addicional package.

```bash
docker run --name drupalci --rm dennisinteractive/drupalci:7-apache \
       --project amp \
       --version dev-1.x \
       --dependencies "drupal/media:* \
                       drupal/ctools:* \
                       drupal/token:* \
                       drupal/google_analytics:* \
                       drupal/dfp:* \
                       drupal/context:* \
                       drupal/adsense:*"
```

### Checking the results
You can mount the verbose folder using -v, then you can see the generated output.

```bash
docker run -v ~/Downloads/results:/results --name drupalci --rm dennisinteractive/drupalci:8-apache \
       --project sharerich \
       --version dev-1.x \
       --dependencies "drupal/token:*"

ls ~/Downloads/verbose
```

### Using is on your project

Copy the [.circleci](https://github.com/dennisinteractive/drupalci/blob/master/8/apache/.circleci) folder into your module, remame config.yml.example to config.yml and enable Circle CI for your project. When you make commits it will automatically trigger the build an you will be able to access the verbose results via Artifacts tab on Circle CI.


### This is standard Docker stuff

Building images

```
./build_all.sh
```

Building and deploying

```
./build_deploy_all.sh
```

## Todos
[x] Add support for Drupal 7

[x] Support patches

[x] PHP script needs to return code 1 when tests fail

[x] Access to results of tests

[x] Pass profile in the arguments or read from the tests

[ ] Add a comment on the top of generated files to say its been generated

[ ] Replace strings on the copied templates i.e. D8 becomes D7

[ ] Rewrite the php scripts to use classes

