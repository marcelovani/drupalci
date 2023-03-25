#!/bin/sh
# Copies all templates and builds the tags
# In order to push you need to run docker login first
# i.e. docker login -u "myusername" -p "mypassword" docker.io

#echo [INFO] Running builds
#sh build_all.sh

DIR=$(pwd)

echo [INFO] Deploying
cd ${DIR}/7/apache \
  && docker push marcelovani/drupalci:7-apache

cd ${DIR}/7/apache-interactive \
  && docker push marcelovani/drupalci:7-apache-interactive

cd ${DIR}/8/apache \
  && docker push marcelovani/drupalci:8-apache

cd ${DIR}/8/apache-interactive \
  && docker push marcelovani/drupalci:8-apache-interactive

cd ${DIR}/9/apache \
  && docker push marcelovani/drupalci:9-apache

cd ${DIR}/9/apache-interactive \
  && docker push marcelovani/drupalci:9-apache-interactive

cd ${DIR}/10/apache \
  && docker push marcelovani/drupalci:10-apache

cd ${DIR}/10/apache-interactive \
  && docker push marcelovani/drupalci:10-apache-interactive
