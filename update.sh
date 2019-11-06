#!/bin/sh
# This script copies the respective files into each folder

#@todo write a loop to parse an array of files to copy i.e.
# {
#	runtests.php: ['7/apache', '8/apache'],
#	entrypoint.sh: ['7/apache', '8/apache'],
# }
#

cp README.md ./7/apache
cp README.md ./8/apache
cp README.md ./9/apache

cp templates/bootstrap.php ./7/apache
cp templates/bootstrap.php ./8/apache
cp templates/bootstrap.php ./9/apache

cp templates/entrypoint.sh ./7/apache
cp templates/entrypoint.sh ./8/apache
cp templates/entrypoint.sh ./9/apache

cp templates/php-overrides.ini ./7/apache
cp templates/php-overrides.ini ./8/apache
cp templates/php-overrides.ini ./9/apache

cp templates/7/runtests.php ./7/apache
cp templates/8/runtests.php ./8/apache
cp templates/9/runtests.php ./9/apache

git status
