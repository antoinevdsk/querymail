#!/bin/bash

function title()
{
    echo ""
    echo -e "\e[92m> $1\e[0m"
}

parent_path=$( cd "$(dirname "${BASH_SOURCE}")" ; pwd -P )

title "Build Query Mail PHP image"
docker build -t querymail-img-php $parent_path/docker

title "PHP Container"
docker rm -f querymail-php 2> /dev/null
docker run --name querymail-php \
        -v $parent_path:/srv/http \
        -v $parent_path/docker/ssmtp.conf:/etc/ssmtp/ssmtp.conf \
        -p 9000:9000 \
        --restart=always \
        -d querymail-img-php

title "Installing libraries"
docker exec querymail-php bash -c "wget https://raw.githubusercontent.com/composer/getcomposer.org/1b137f8bf6db3e79a38a5bc45324414a6b1f9df2/web/installer -O - -q | php -- --quiet && mv composer.phar /usr/bin/composer && cd /srv/http && composer install --no-dev -o"


if [ ! -f $parent_path/sqlite/querymail ]; then
    title "Installing database"
    docker exec querymail-php bash -c "sqlite3 /srv/http/sqlite/querymail < /srv/http/sqlite/querymail.sql"
fi

title "Nginx container"
docker rm -f querymail-nginx 2> /dev/null
docker run --name querymail-nginx \
        -v $parent_path:/usr/share/nginx:ro \
        -v $parent_path/docker/nginx.conf:/nginx.conf \
        --restart=always \
        -p 80:80 \
        --link querymail-php \
        -d nginx \
        nginx -c /nginx.conf