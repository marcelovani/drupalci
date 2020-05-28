#!make

build-deploy:
	make build
	make deploy

build:
	./build_all.sh

deploy:
	./build_deploy_all.sh

test:
	docker run --name drupalcitest --rm marcelovani/drupalci:7-apache --project adstxt --version ^1.0.0
	docker run --name drupalcitest --rm marcelovani/drupalci:8-apache --project adstxt --version ^1.0.0
	docker run --name drupalcitest --rm marcelovani/drupalci:9-apache --project adstxt --version ^1.0.0
