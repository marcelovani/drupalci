#!/bin/sh
# This script copies the respective files into each folder

#@todo write a loop to parse an array of files to copy i.e.
# {
#	runtests.php: ['7/apache', '8/apache', ['8/fpm-alpine']],
#	entrypoint.sh: ['7/apache', '8/apache', ['8/fpm-alpine']],
# }
#
#@todo add a comment on the top of generated files to say its been generated
#

cp templates/bootstrap.php ./7/apache
cp templates/bootstrap.php ./8/apache
cp templates/bootstrap.php ./8/fpm-alpine

cp templates/entrypoint.sh ./7/apache
cp templates/entrypoint.sh ./8/apache
cp templates/entrypoint.sh ./8/fpm-alpine

cp templates/php-overrides.ini ./7/apache
cp templates/php-overrides.ini ./8/apache
cp templates/php-overrides.ini ./8/fpm-alpine

cp templates/7/runtests.php ./7/apache
cp templates/8/runtests.php ./8/apache
cp templates/8/runtests.php ./8/fpm-alpine

git status
