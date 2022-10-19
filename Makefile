#!make

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

test-7:
	docker run --name drupalcitest --rm marcelovani/drupalci:7-apache --project adstxt --version ^1.0.0

test-8:
	docker run --name drupalcitest --rm marcelovani/drupalci:8-apache --project adstxt --version ^1.0.0 --patches https://www.drupal.org/files/issues/2020-02-04/3110931-2.patch

test-9:
	docker run --name drupalcitest --rm marcelovani/drupalci:9-apache --project adstxt --version ^1.0.0

test-10:
	docker run --name drupalcitest --rm marcelovani/drupalci:10-apache --project token --version ^1.0.0
