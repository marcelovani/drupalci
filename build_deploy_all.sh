#!/bin/sh
# Copies all templates and builds the tags
# In order to push you need to run docker login first
# i.e. docker login -u "myusername" -p "mypassword" docker.io

echo [INFO] Running builds
sh build_all.sh

echo [INFO] Deploying
cd ./7/apache \
  && docker push marcelovani/drupalci:7-apache \
  && cd ../../

cd ./7/apache-interactive \
  && docker push marcelovani/drupalci:7-apache-interactive \
  && cd ../../

cd ./8/apache \
  && docker push marcelovani/drupalci:8-apache \
  && cd ../../

cd ./8/apache-interactive \
  && docker push marcelovani/drupalci:8-apache-interactive \
  && cd ../../

cd ./9/apache \
  && docker push marcelovani/drupalci:9-apache \
  && cd ../../

cd ./9/apache-interactive \
  && docker push marcelovani/drupalci:9-apache-interactive \
  && cd ../../
