#!/bin/sh

sh update.sh

DIR=$(pwd)

echo "[1;104m"
echo "\033[1;33m[INFO] Build drupalci:11-apache\033" \
  && cd ${DIR}/11/apache \
  && docker build --force-rm -t marcelovani/drupalci:11-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:11-apache-interactive" \
  && cd ${DIR}/11/apache-interactive \
  && docker build --force-rm -t marcelovani/drupalci:11-apache-interactive .

echo "\033[1;0m"

