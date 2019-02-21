#!/bin/sh
exec /usr/sbin/apache2ctl -D FOREGROUND &

php runtests.php $1 $2 $3 $4 $5 $6 $7 $8
