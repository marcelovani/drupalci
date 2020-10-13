#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

DIR=$(pwd)

echo "\033[1;0m"
clear

echo "\033[1;104m"
echo "\033[1;33m[INFO] Build drupalci:7-apache" \
  && cd ${DIR}/7/apache \
  && docker build --force-rm -t marcelovani/drupalci:7-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:7-apache-interactive" \
  && cd ${DIR}/7/apache-interactive \
  && docker build --force-rm -t marcelovani/drupalci:7-apache-interactive .

echo "\033[1;104m"
echo "\033[1;33m[INFO] Build drupalci:8-apache" \
  && cd ${DIR}/8/apache \
  && docker build --force-rm -t marcelovani/drupalci:8-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:8-apache-interactive" \
  && cd ${DIR}/8/apache-interactive \
  && docker build --force-rm -t marcelovani/drupalci:8-apache-interactive .

echo "[1;104m"
echo "\033[1;33m[INFO] Build drupalci:9-apache\033" \
  && cd ${DIR}/9/apache \
  && docker build --force-rm -t marcelovani/drupalci:9-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:9-apache-interactive" \
  && cd ${DIR}/9/apache-interactive \
  && docker build --force-rm -t marcelovani/drupalci:9-apache-interactive .

echo "\033[1;0m"

