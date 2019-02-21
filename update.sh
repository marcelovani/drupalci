#!/bin/sh
# This script copies the respective files into each folder

#@todo write a loop
cp runtests.php ./7/apache
cp runtests.php ./8/apache
cp runtests.php ./8/fpm-alpine

cp entrypoint.sh ./7/apache
cp entrypoint.sh ./8/apache
cp entrypoint.sh ./8/fpm-alpine

cp php-overrides.ini ./7/apache
cp php-overrides.ini ./8/apache
cp php-overrides.ini ./8/fpm-alpine

git diff
