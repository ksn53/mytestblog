#!/usr/bin/env sh

# do something to init project
# this file exec's by jenkins after docker container up
cd /var/www

set -e

#chmod -R g+w /var/www
#chown -R www-data /var/www/

cp -RTpu /var/composer/vendor /var/www/vendor

chmod -R g+rwx /var/www/vendor
chmod -R g+rwx /var/www/web

if [ -n "${DOCKER_UID}" ] ; then
    if [ -n "${DOCKER_GID}" ] ; then
        chown -R $DOCKER_UID:$DOCKER_GID /var/www/vendor
        chown -R $DOCKER_UID:$DOCKER_GID /var/www/web
    fi
fi

composer dump-autoload --no-scripts --optimize

chmod -R g+rwx /var/www/var

if [ -n "${DOCKER_UID}" ] ; then
    if [ -n "${DOCKER_GID}" ] ; then
        chown -R $DOCKER_UID:$DOCKER_GID /var/www/var
    fi
fi
