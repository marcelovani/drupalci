#!make
# @todo use it like this https://github.com/jenkinsci/docker-ssh-agent/blob/5.23.0/Makefile
# https://github.com/jenkinsci/docker-ssh-agent/blob/5.23.0/README.md
# https://github.com/jenkinsci/docker-agent/blob/master/Makefile

build:
	./build_all.sh

deploy:
	./build_deploy_all.sh

build-deploy:
	make build deploy

stop-all-containers:
	ids=$$(docker ps -a -q) && if [ "$${ids}" != "" ]; then docker stop $${ids}; fi

test:
	make test-7
	make test-8
	make test-9
	make test-10
	make test-11
	make test-12

test-7:
	docker run --name drupalcitest --rm marcelovani/drupalci:7-apache --project adstxt --version ^1.0.0

test-8:
	docker run --name drupalcitest --rm marcelovani/drupalci:8-apache --project adstxt --version ^1.0.0 --patches https://www.drupal.org/files/issues/2020-02-04/3110931-2.patch

test-9:
	docker run --name drupalcitest --rm marcelovani/drupalci:9-apache --project adstxt --version ^1.0.0

test-10:
	docker run --name drupalcitest --rm marcelovani/drupalci:10-apache --project acquia_vwo --version ^1.0.0

# token 1.x's own suite no longer passes on current core, so it can no longer
# gate a release of these images: BookTest needs the book module that left core
# in 11, and FieldTest asserts field-type descriptions core has since reworded.
# config_token is a smaller subject with a `>=8` core_version_requirement, so
# the same project installs on both 11 and 12; token_filter is its
# test_dependency and run-tests.sh refuses to run without it.
test-11:
	docker run --name drupalcitest --rm marcelovani/drupalci:11-apache --project config_token --version ^1.0.0 --dependencies drupal/token_filter

# 12 takes config_token's dev branch, not the 1.7 release: Drupal 12 turned
# missing #[Group] metadata from a deprecation into a MissingGroupException,
# and only the dev branch carries the attribute on its test classes.
test-12:
	docker run --name drupalcitest --rm marcelovani/drupalci:12-apache --project config_token --version dev-1.x --dependencies drupal/token_filter
