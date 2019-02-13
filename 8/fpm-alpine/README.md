#DrupalCi

Runs Drupal webtests and php unit tests using Docker containers.
This can be used with Git webhooks but its not ready yet.

##Usage examples
The parameters for the runtests.sh script are:
* Drupal version
* Project name
* Project version
* Fork url [optional]

Run tests from the a released version of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "sh runtests.sh 8 captcha_keypad 1.x"
```

Run tests from the main dev branch of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "sh runtests.sh 8 captcha_keypad 1.x-dev"
```

Run tests from the a forked branch of Captcha Keypad module

```bash
docker run --name drupalci --rm marcellovani/drupalci:8-fpm-alpine /bin/sh -c "sh runtests.sh 8 captcha_keypad broken_test-dev https://github.com/marcelovani/captcha_keypad.git"
```

## Viewing tests results
@Todo write the steps
