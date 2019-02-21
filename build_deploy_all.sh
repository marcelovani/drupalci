#!/bin/sh
# Copies all templates and builds the tags

sh build_all.sh

cd ./7/apache \
  && docker tag drupal:8-apache marcellovani/drupalci:7-apache \
  && docker push marcellovani/drupalci:7-apache \
  && cd ../../

cd ./8/apache \
  && docker tag drupal:8-apache marcellovani/drupalci:8-apache \
  && docker push marcellovani/drupalci:8-apache \
  && cd ../../

