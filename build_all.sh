#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

echo [RUN] Build drupalci:7-apache \
  && clear \
  && cd ./7/apache \
  && docker build -t marcelovani/drupalci:7-apache . \
  && cd ../../

echo [RUN] Build drupalci:8-apache \
  && clear \
  && cd ./8/apache \
  && docker build -t marcelovani/drupalci:8-apache . \
  && cd ../../
