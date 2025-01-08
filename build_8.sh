#!/bin/sh

sh update.sh

DIR=$(pwd)

echo "\033[1;104m"
echo "\033[1;33m[INFO] Build drupalci:8-apache" \
  && cd ${DIR}/8/apache \
  && docker buildx build --force-rm -t marcelovani/drupalci:8-apache .

echo "\033[1;100m"
echo "\033[1;33m[INFO] Build drupalci:8-apache-interactive" \
  && cd ${DIR}/8/apache-interactive \
  && docker buildx build --force-rm -t marcelovani/drupalci:8-apache-interactive .

