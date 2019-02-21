# DrupalCi

[![Docker build auto](https://img.shields.io/docker/automated/marcellovani/drupalci.svg)](https://hub.docker.com/r/marcellovani/drupalci)
[![Docker pulls](https://img.shields.io/docker/pulls/marcellovani/drupalci.svg)](https://hub.docker.com/r/marcellovani/drupalci)
[![Docker build status](https://img.shields.io/docker/status/marcellovani/drupalci.svg)](https://hub.docker.com/r/marcellovani/drupalci) 

Runs Drupal webtests and php unit tests using Docker containers.
This can be used with Git webhooks but its not ready yet.

## Usage examples
The parameters for the runtests.sh script are:
* --project   Project or module name
* --version   Project version or branch name [optional]
* --vcs       Fork url [optional]
* --profile   Drupal install profile [optional]

### Usage
Run tests from the a released version of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-apache \
       --project captcha_keypad \
       --version ^1.0.0
```

### Forks and branches
To run tests from the a forked branch you can use --version with the branch name plus -dev.
You can also specify the repository using --vcs.

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-apache \
       --project captcha_keypad \
       --version 8.x-1.x-fork-dev \
       --vcs https://github.com/marcelovani/captcha_keypad.git
```

### Checking the results
You can mount the verbose folder using -v, then you can see the generated output.

```bash
docker run -v ~/Downloads/results:/results --name drupalci --rm marcellovani/drupalci:8-apache \
       --project captcha_keypad \
       --version 8.x-1.x-dev \
       --vcs https://github.com/marcelovani/captcha_keypad.git

ls ~/Downloads/verbose
```

### Using is on your project

Copy the .circleci folder into your module, remame config.yml.example to config.yml and enable Circle CI for your project. When you make commits it will automatically trigger the build an you will be able to access the verbose results via Artifacts tab on Circle CI.


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

[ ] Support patches

[x] PHP script needs to return code 1 when tests fail

[x] Access to results of tests

[x] Pass profile in the arguments or read from the tests

[ ] Add a comment on the top of generated files to say its been generated

[ ] Replace strings on the copied templates i.e. D8 becomes D7
