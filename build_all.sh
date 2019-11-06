#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

echo "\033[1;33m[INFO] Build drupalci:7-apache\033[1;0m" \
  && cd ./7/apache \
  && docker build -t marcelovani/drupalci:7-apache . \
  && cd ../../

echo "\033[1;33m[INFO] Build drupalci:7-apache-interactive\033[1;0m" \
  && cd ./7/apache-interactive \
  && docker build -t marcelovani/drupalci:7-apache-interactive . \
  && cd ../../

echo "\033[1;33m[INFO] Build drupalci:8-apache\033[1;0m" \
  && cd ./8/apache \
  && docker build -t marcelovani/drupalci:8-apache . \
  && cd ../../

echo "\033[1;33m[INFO] Build drupalci:8-apache-interactive\033[1;0m" \
  && cd ./8/apache-interactive \
  && docker build -t marcelovani/drupalci:8-apache-interactive . \
  && cd ../../

echo "\033[1;33m[INFO] Build drupalci:9-apache\033[1;0m" \
  && cd ./9/apache \
  && docker build -t marcelovani/drupalci:9-apache . \
  && cd ../../

echo "\033[1;33m[INFO] Build drupalci:9-apache-interactive\033[1;0m" \
  && pwd && ls -al &&  cd ./9/apache-interactive \
  && docker build -t marcelovani/drupalci:9-apache-interactive . \
  && cd ../../
