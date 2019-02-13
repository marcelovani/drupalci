#!/bin/sh
set -x

drupal_version=$1
require_module=$2
module_version=$3
vcs=$4

cd /var/www/html

if [[ -z "$drupal_version" ]]; then
   # this option could be deprecated
   echo "You need to specify the Drupal version/branch i.e. ${0} 8"
   exit 1
fi

if [[ -z "$require_module" ]]; then
   echo "You need to specify the Drupal module i.e. ${0} 8 boxout"
   exit 1
fi

if [[ -z "$module_version" ]]; then
   echo "You need to specify the module version/branch i.e. ${0} 8 boxout my_branch-dev"
   exit 1
fi

if [[ ! -z "$vcs" ]]; then
   composer config repositories.${require_module} '{"type": "vcs", "no-api": true, "url": "'${vcs}'"}'
fi

composer require drupal/${require_module}:${module_version}

php core/scripts/run-tests.sh --php /usr/local/bin/php --color --concurrency "31" --sqlite sites/default/files/.ht.sqlite --verbose --directory "modules/contrib/${require_module}"
