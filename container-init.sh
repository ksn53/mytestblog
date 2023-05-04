#!/usr/bin/env sh

if ! /usr/bin/env docker-compose exec -T php sh /var/www/init.sh; then
    echo "Running init.sh failed"
    exit 1;
fi;
