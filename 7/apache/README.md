# DrupalCi

[![docker buildx build auto](https://img.shields.io/docker/automated/marcelovani/drupalci.svg)](https://hub.docker.com/r/marcelovani/drupalci)
[![Docker pulls](https://img.shields.io/docker/pulls/marcelovani/drupalci.svg)](https://hub.docker.com/r/marcelovani/drupalci)

Runs Drupal webtests and php unit tests using Docker containers.
This can be used with Git webhooks but its not ready yet.

## Usage examples
The parameters for the runtests.php script are:
* --project      Project or module name
* --version      Project version or branch name [optional]. The format is the same as used in [Composer](https://getcomposer.org/doc/04-schema.md#version)
* --vcs          Fork url [optional]
* --profile      Drupal install profile [optional]
* --patches      Specifies a list of patches to be applied. [See example below](#Patches).
* --dependencies List of test dependencies [optional] i.e. "drupal/link:* drupal/email:^1.0"

### Drupal 10, Drupal 11
Run tests from the a released version of AdsTxt module

```bash
docker pull marcelovani/drupalci:11-apache
docker run --name drupalci --rm marcelovani/drupalci:11-apache \
       --project adstxt \
       --version ^1.0.0

### Drupal 9
Run tests from the a released version of AdsTxt module

```bash
docker pull marcelovani/drupalci:9-apache
docker run --name drupalci --rm marcelovani/drupalci:9-apache \
       --project adstxt \
       --version ^1.0.0
```

### Drupal 8
Run tests from the a released version of Captcha Keypad module

```bash
docker pull marcelovani/drupalci:8-apache
docker run --name drupalci --rm marcelovani/drupalci:8-apache \
       --project captcha_keypad \
       --version ^1.0.0
```

### Drupal 7

```bash
docker pull marcelovani/drupalci:7-apache
docker run --name drupalci --rm marcelovani/drupalci:7-apache \
       --project captcha_keypad \
       --version ^1.0.0
```

### Interactive mode
The container will not run tests automatically.
It is useful to perform manual tasks for the purposes of debugging.

Starting the server
```bash
docker run --rm --name drupalci -p 8080:80 -d marcelovani/drupalci:11-apache-interactive
```

Using a mounted folder for a custom module, in this example we are using **adstxt** module
```bash
docker run --rm --name drupalci -v ~/adstxt:/var/www/html/web/modules/contrib/adstxt -p 8080:80 -d marcelovani/drupalci:11-apache-interactive
``` 

Getting into the container
```bash
docker exec -it drupalci bash
```

Running tests manually
```
cd web
sudo -u www-data php core/scripts/drupal install minimal
sudo -u www-data php core/scripts/run-tests.sh --php /usr/local/bin/php --verbose --keep-results --color --concurrency "32" --repeat "1" --types "Simpletest,PHPUnit-Unit,PHPUnit-Kernel,PHPUnit-Functional" --sqlite sites/default/files/.ht.sqlite --url http://localhost --directory "modules/contrib/adstxt"
```

To install the site using sql lite and Drush, use

`drush si --db-url=sqlite://:memory`

See more details in https://www.drush.org/12.x/commands/site_install

Opening in the browser
```bash
open http://localhost:8080
```

Stopping the container
```bash
docker stop drupalci
```

### Forks and branches
To run tests from the a forked branch you can use --version with the branch.
See [Non feature branches](https://getcomposer.org/doc/04-schema.md#non-feature-branches).
You can also specify the repository using --vcs.

```bash
docker pull marcelovani/drupalci:8-apache
docker run --name drupalci --rm marcelovani/drupalci:8-apache \
       --project captcha_keypad \
       --version dev-8.x-1.x \
       --vcs https://github.com/marcelovani/captcha_keypad.git
```

### Patches
You can provide a list of patches to be applied to the project.

```bash
docker pull marcelovani/drupalci:7-apache
docker run --name drupalci --rm marcelovani/drupalci:7-apache \
       --project amp \
       --version dev-1.x \
       --patches https://www.drupal.org/files/issues/2019-02-11/amp-initial-page-load-3031306-18.patch
```
 
For multiple patches, each Url needs to be separated by comma.

```bash
docker run --name drupalci --rm marcelovani/drupalci:7-apache \
       --project captcha_keypad \
       --version dev-1.x \
       --patches "https://www.example.com/fix-1.patch, https://www.example.com/fix-2.patch"
```

### Dependencies
Used to install test dependencies or any additional package.

```bash
docker run --name drupalci --rm marcelovani/drupalci:7-apache \
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
docker run -v ~/Downloads/artifacts:/artifacts --name drupalci --rm marcelovani/drupalci:8-apache \
       --project sharerich \
       --version dev-1.x \
       --dependencies "drupal/token:*"

ls ~/Downloads/artifacts
```

### Using is on your project

Copy the [.circleci](https://github.com/marcelovani/drupalci/blob/master/8/apache/.circleci) folder into your module, remame config.yml.example to config.yml and enable Circle CI for your project. When you make commits it will automatically trigger the build an you will be able to access the verbose results via Artifacts tab on Circle CI.


### This is standard Docker stuff

Building images

```
make build
```

Building and deploying

```
make deploy
```

## Jenkins
You can use these images on jenkins to automate your buildings

[How to configure Jenkins](https://github.com/marcelovani/drupalci/blob/master/Jenkins "How to configure Jenkins")
[![See how to configure Jenkins](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/0-jenkins_job.jpg)](https://github.com/marcelovani/drupalci/blob/master/Jenkins)

## Contributing
Each version of Drupal has its own Dockerfile with customisations.
The templates folder contains the commands for each Drupal and bootstrap.
Currently, there is support for Drupal 7, 8, 9 and 10.

### Building and updating
To build all, upgrading packages, use `make build`
To deploy all, use `make deploy`
To build and deploy all, use `make build-deploy`
ps: Deploy will push the image to Docker hub.

### Testing
To test all, use `make test`
To test individual Drupal, use specific Drupal version `make test-10`
ps: Always test all Drupal versions before deploying.

### Chrome Driver
You can use Drupal [CI Chrome driver](https://hub.docker.com/r/drupalci/webdriver-chromedriver/tags) to run Functional Javascript tests.

```bash
docker run --rm --name chromedriver -p 9515:9515 -d drupalci/webdriver-chromedriver:production
```

Alternatively you can use Selenium, see https://github.com/lando/drupal/issues/27

#### Chrome Driver - Local Development
Starting the Chrome Driver server locally
```bash
npm install
npm start
```

Then test if Chrome Driver is working
```bash
npm test
```

Looking at the Chrome Driver logs
```bash
npm run show_logs
```
