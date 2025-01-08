#!/bin/sh

sh update.sh

DIR=$(pwd)

echo "\033[1;104m"
echo "\033[1;33m[INFO] Build drupalci:7-apache" \
  && cd ${DIR}/7/apache \
  && docker buildx build --force-rm -t marcelovani/drupalci:7-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:7-apache-interactive" \
  && cd ${DIR}/7/apache-interactive \
  && docker buildx build --force-rm -t marcelovani/drupalci:7-apache-interactive .

