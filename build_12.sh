#!/bin/sh

sh update.sh

DIR=$(pwd)

echo "[1;104m"
echo "\033[1;33m[INFO] Build drupalci:12-apache\033" \
  && cd ${DIR}/12/apache \
  && docker buildx build --force-rm -t marcelovani/drupalci:12-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:12-apache-interactive" \
  && cd ${DIR}/12/apache-interactive \
  && docker buildx build --force-rm -t marcelovani/drupalci:12-apache-interactive .

echo "\033[1;0m"
