#!/bin/sh
# Copies all templates and builds the tags

sh update.sh

DIR=$(pwd)

echo "\033[1;0m"
clear

sh build_7.sh
sh build_8.sh
sh build_9.sh
sh build_10.sh
sh build_11.sh

echo "\033[1;0m"

git status

