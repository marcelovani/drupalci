#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

cd ./7/apache \
  && docker build -t marcelovani/drupalci:7-apache . \
  && cd ../../

cd ./8/apache \
  && docker build -t marcelovani/drupalci:8-apache . \
  && cd ../../
