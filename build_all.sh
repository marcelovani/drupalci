#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

echo [INFO] Build drupalci:7-apache \
  && cd ./7/apache \
  && docker build -t marcelovani/drupalci:7-apache . \
  && cd ../../

echo [INFO] Build drupalci:7-apache-interactive \
  && cd ./7/apache-interactive \
  && docker build -t marcelovani/drupalci:7-apache-interactive . \
  && cd ../../

echo [INFO] Build drupalci:8-apache \
  && cd ./8/apache \
  && docker build -t marcelovani/drupalci:8-apache . \
  && cd ../../

echo [INFO] Build drupalci:8-apache-interactive \
  && cd ./8/apache-interactive \
  && docker build -t marcelovani/drupalci:8-apache-interactive . \
  && cd ../../

echo [INFO] Build drupalci:9-apache \
  && cd ./9/apache \
  && docker build -t marcelovani/drupalci:9-apache . \
  && cd ../../

echo [INFO] Build drupalci:9-apache-interactive \
  && pwd && ls -al &&  cd ./9/apache-interactive \
  && docker build -t marcelovani/drupalci:9-apache-interactive . \
  && cd ../../
