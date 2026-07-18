# DrupalCi

[![docker buildx build auto](https://img.shields.io/docker/automated/marcelovani/drupalci.svg)](https://hub.docker.com/r/marcelovani/drupalci)
[![Docker pulls](https://img.shields.io/docker/pulls/marcelovani/drupalci.svg)](https://hub.docker.com/r/marcelovani/drupalci)

Runs Drupal webtests and php unit tests using Docker containers.
This can be used with Git webhooks but its not ready yet.

## Usage examples

The parameters for the runtests.php script are:

- --project Project or module name
- --version Project version or branch name [optional]. The format is the same as used in [Composer](https://getcomposer.org/doc/04-schema.md#version)
- --vcs Fork url [optional]
- --profile Drupal install profile [optional]
- --patches Specifies a list of patches to be applied. [See example below](#Patches).
- --dependencies List of test dependencies [optional] i.e. "drupal/link:\* drupal/email:^1.0"

### Drupal 12

Run tests from a released version of the AdsTxt module

```bash
docker pull marcelovani/drupalci:12-apache
docker run --name drupalci --rm marcelovani/drupalci:12-apache \
       --project adstxt \
       --version ^1.0.0
```

### Drupal 10, Drupal 11

Run tests from the a released version of AdsTxt module

```bash
docker pull marcelovani/drupalci:11-apache
docker run --name drupalci --rm marcelovani/drupalci:11-apache \
       --project adstxt \
       --version ^1.0.0
```

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

### Local Development

#### Prerequisites

- Docker (with `docker buildx` support)
- GNU Make
- Git

#### Repository layout

```
drupalci/
├── Makefile                  # Top-level targets: build, deploy, test, test-N
├── build_all.sh              # Runs every build_N.sh in order
├── build_deploy_all.sh       # Pushes every image to Docker Hub
├── build_N.sh                # Builds the two images for Drupal version N
├── update.sh                 # Copies shared files from templates/ into each N/apache/
├── update_11.sh (optional)   # Version-specific overrides (sqlite.patch etc.)
├── templates/
│   ├── bootstrap.php         # Shared across all versions
│   ├── entrypoint.sh         # Shared across all versions
│   ├── php-overrides.ini     # Shared PHP ini tweaks
│   └── N/
│       └── runtests.php      # Version-specific test runner script
├── N/
│   ├── apache/
│   │   ├── Dockerfile        # Main image (extends official drupal:N-phpX.Y-apache)
│   │   ├── bootstrap.php     # Copied from templates/ by update.sh
│   │   ├── entrypoint.sh     # Copied from templates/ by update.sh
│   │   ├── php-overrides.ini # Copied from templates/ by update.sh
│   │   ├── runtests.php      # Copied from templates/N/ by update.sh
│   │   └── README.md         # Copied from root README.md by update.sh
│   └── apache-interactive/
│       └── Dockerfile        # Thin layer — extends marcelovani/drupalci:N-apache
└── tests/                    # Chrome Driver smoke tests
```

#### How the template system works

`update.sh` is the source-of-truth copier. It pushes shared files (`bootstrap.php`,
`entrypoint.sh`, `php-overrides.ini`, `README.md`) into every `N/apache/` directory,
then copies the version-specific `templates/N/runtests.php` to `N/apache/`.

**Always edit templates, never the copies inside `N/apache/`** — they will be
overwritten next time `update.sh` runs (which `build_all.sh` calls automatically).

#### Building a single version locally

```bash
# Refresh all template copies first
./update.sh

# Build just Drupal 11
./build_11.sh

# Or build and tag manually
cd 11/apache
docker buildx build --force-rm -t marcelovani/drupalci:11-apache .
```

#### Running a quick smoke test

```bash
make test-11
```

This pulls nothing — it runs the locally-built image against a known module.

#### Build → test → deploy workflow

```bash
make build        # ./build_all.sh (runs update.sh + every build_N.sh)
make test         # smoke-tests every version
make deploy       # docker push all images to Docker Hub (requires docker login)
```

#### Adding support for a new Drupal version (e.g. Drupal 12)

The following steps are required. Each one mirrors what was done when Drupal 11
was added alongside Drupal 10.

1. **Create the directory structure**

   ```
   12/apache/
   12/apache-interactive/
   templates/12/
   ```

2. **Add `templates/12/runtests.php`**
   Copy from `templates/11/runtests.php` and adjust any version-specific flags
   (e.g. `--suppress-deprecations` strategy, PHP constraint).

3. **Add `12/apache/Dockerfile`**
   Copy from `11/apache/Dockerfile`.  
   Key changes:
   - `FROM drupal:12-php8.3-apache` (adjust PHP version when official image is published)
   - `ENV DRUPAL_VERSION=12`
   - Verify whether `sqlite.patch` is still needed (it was introduced for D11)
   - Update `composer create-project` constraint to `^12`

4. **Add `12/apache-interactive/Dockerfile`**
   Copy from `11/apache-interactive/Dockerfile` and replace `11` → `12`.

5. **Add `build_12.sh`**
   Copy from `build_11.sh` and replace `11` → `12`.

6. **Update `update.sh`**
   Add lines to copy shared templates into `12/apache/`:

   ```sh
   cp README.md ./12/apache
   cp templates/bootstrap.php ./12/apache
   cp templates/entrypoint.sh ./12/apache
   cp templates/php-overrides.ini ./12/apache
   cp templates/12/runtests.php ./12/apache
   ```

7. **Update `build_all.sh`**
   Add `sh build_12.sh` after the `build_11.sh` call.

8. **Update `build_deploy_all.sh`**
   Add push commands for `12-apache` and `12-apache-interactive`.

9. **Update `Makefile`**
   - Add `make test-12` target
   - Add `make test-12` to the `test:` target

10. **Update `README.md`**
    - Add a Drupal 12 usage example under "Usage examples"
    - Update the "Currently, there is support for..." line in Contributing

Each version of Drupal has its own Dockerfile with customisations.
The templates folder contains the commands for each Drupal and bootstrap.
Currently, there is support for Drupal 7, 8, 9, 10 and 11.

### Building and updating

To build all, upgrading packages, use `make build`
To deploy all, use `make deploy`
To build and deploy all, use `make build-deploy`
ps: Deploy will push the image to Docker hub.

### GitHub Actions (automated build/test/deploy)

Instead of running `make build`/`make test`/`make deploy` by hand, you can trigger the
`Build, test and deploy DrupalCI images` workflow from the repo's **Actions** tab, or via:

```bash
gh workflow run build-test-deploy.yml --ref master \
  -f build_7=false -f build_8=false -f build_9=false \
  -f build_10=true -f build_11=true -f build_12=true
```

Each checked version is built and smoke-tested independently (`fail-fast: false`) — one
version's test failure doesn't block the others. A version is only pushed to Docker Hub if
its smoke test passed.

**One-time setup:** the workflow needs two repository secrets under Settings → Secrets and
variables → Actions:

- `DOCKERHUB_USERNAME` — your Docker Hub username
- `DOCKERHUB_TOKEN` — a Docker Hub **access token** (Account Settings → Security → New
  Access Token on hub.docker.com), not your account password

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

docker run --rm --name chromedriver -p 9515:9515 -d drupalci/webdriver-chromedriver:production

````

Alternatively you can use Selenium, see https://github.com/lando/drupal/issues/27

#### Chrome Driver - Local Development

Starting the Chrome Driver server locally

```bash
npm install
npm start
`npm start
````

Then test if Chrome Driver is working

```bash
npm test
```

Looking at the Chrome Driver logs

```bash
npm run show_logs
```
