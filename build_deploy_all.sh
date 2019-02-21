#!/bin/sh
# Copies all templates and builds the tags

sh build_all.sh

cd ./7/apache \
  && docker tag drupal:8-apache marcelovani/drupalci:7-apache \
  && docker push marcelovani/drupalci:7-apache \
  && cd ../../

cd ./8/apache \
  && docker tag drupal:8-apache marcelovani/drupalci:8-apache \
  && docker push marcelovani/drupalci:8-apache \
  && cd ../../

