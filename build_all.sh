#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

echo "\033[1;0m"
clear

echo "\033[1;104m"
echo "\033[1;33m[INFO] Build drupalci:7-apache" \
  && cd ./7/apache \
  && docker build -t marcelovani/drupalci:7-apache . \
  && cd ../../

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:7-apache-interactive" \
  && cd ./7/apache-interactive \
  && docker build -t marcelovani/drupalci:7-apache-interactive . \
  && cd ../../

echo "\033[1;104m"
echo "\033[1;33m[INFO] Build drupalci:8-apache" \
  && cd ./8/apache \
  && docker build -t marcelovani/drupalci:8-apache . \
  && cd ../../

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:8-apache-interactive" \
  && cd ./8/apache-interactive \
  && docker build -t marcelovani/drupalci:8-apache-interactive . \
  && cd ../../

echo "[1;104m"
echo "\033[1;33m[INFO] Build drupalci:9-apache\033" \
  && cd ./9/apache \
  && docker build -t marcelovani/drupalci:9-apache . \
  && cd ../../

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:9-apache-interactive" \
  && pwd && ls -al &&  cd ./9/apache-interactive \
  && docker build -t marcelovani/drupalci:9-apache-interactive . \
  && cd ../../

echo "\033[1;0m"

