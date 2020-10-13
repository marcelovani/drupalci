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
	docker run --name drupalcitest --rm marcelovani/drupalci:7-apache --project adstxt --version ^1.0.0
	docker run --name drupalcitest --rm marcelovani/drupalci:8-apache --project adstxt --version ^1.0.0
	docker run --name drupalcitest --rm marcelovani/drupalci:9-apache --project adstxt --version ^1.0.0
