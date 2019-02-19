# DrupalCi

Runs Drupal webtests and php unit tests using Docker containers.
This can be used with Git webhooks but its not ready yet.

## Usage examples
The parameters for the runtests.sh script are:
* --profile   Drupal install profile [optinoal, default = 'minimal']
* --project   Project or module name
* --version   Project version or branch name
* --vcs       Fork url [optional]

Run tests from the a released version of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "php runtests.sh --project captcha_keypad --version 1.x"
```

Run tests from the main dev branch of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "php runtests.sh --project drupal/captcha_keypad --version 1.x-dev"
```

Run tests from the a forked branch of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "php runtests.sh --profile standard --project captcha_keypad --version broken_test-dev --vcs https://github.com/marcelovani/captcha_keypad.git"
```

Keeping the results


```bash
docker run -v /tmp/drupalci/verbose:/var/www/html/sites/default/files/simpletest/verbose --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "php runtests.sh --project captcha_keypad --version 8.x-1.x-dev --vcs https://github.com/marcelovani/captcha_keypad.git"
ls /tmp/drupalci/verbose
```

Viewing the results


```bash
ls /tmp/drupalci/verbose
```

### Using is on your Drupal module

Copy the .circleci folder into your module, remame config.yml.example to config.yml and enable Circle CI for your project. When you make commits it will automatically trigger the build an you will be able to access the verbose results via Artifacts tab on Circle CI.


### This is standard Docker stuff

Building an image

```
docker build -t marcellovani/drupalci:8-fpm-alpine .
```

Pushing a tag

```
docker tag drupal:8-fpm-alpine drupalci:8-fpm-alpine
docker push marcellovani/drupalci:8-fpm-alpine
```

## Todos
[ ] Add support for Drupal 7

[ ] Support patches

[ ] PHP script needs to return code 1 when tests fail

[x] Access to results of tests

[x] Pass profile in the arguments or read from the tests
