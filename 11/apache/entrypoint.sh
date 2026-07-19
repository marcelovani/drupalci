#!/bin/sh
rsyslogd

exec /usr/sbin/apache2ctl -D FOREGROUND &

clear

php bootstrap.php "$@"
