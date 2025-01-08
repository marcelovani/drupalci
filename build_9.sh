#!/bin/sh

sh update.sh

DIR=$(pwd)

echo "[1;104m"
echo "\033[1;33m[INFO] Build drupalci:9-apache\033" \
  && cd ${DIR}/9/apache \
  && docker buildx build --force-rm -t marcelovani/drupalci:9-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:9-apache-interactive" \
  && cd ${DIR}/9/apache-interactive \
  && docker buildx build --force-rm -t marcelovani/drupalci:9-apache-interactive .
