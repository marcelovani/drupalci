#!/bin/sh

sh update.sh

DIR=$(pwd)

echo "[1;104m"
echo "\033[1;33m[INFO] Build drupalci:10-apache\033" \
  && cd ${DIR}/10/apache \
  && docker buildx build --force-rm -t marcelovani/drupalci:10-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:10-apache-interactive" \
  && cd ${DIR}/10/apache-interactive \
  && docker buildx build --force-rm -t marcelovani/drupalci:10-apache-interactive .

echo "\033[1;0m"
