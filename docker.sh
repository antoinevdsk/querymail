#!/bin/bash

function title()
{
    echo ""
    echo -e "\e[92m> $1\e[0m"
}

parent_path=$( cd "$(dirname "${BASH_SOURCE}")" ; pwd -P )

title "build php image"
docker build -t querymail-img-php $parent_path/docker

title "run docker php container"
docker rm -f querymail-php 2> /dev/null
docker run --name querymail-php \
        -v $parent_path:/srv/http \
        -v $parent_path/docker/ssmtp.conf:/etc/ssmtp/ssmtp.conf \
        -p 9000:9000 \
        --restart=always \
        -d querymail-img-php

title "nginx container"
docker rm -f querymail-nginx 2> /dev/null
docker run --name querymail-nginx \
        -v $parent_path:/usr/share/nginx:ro \
        -v $parent_path/docker/nginx.conf:/nginx.conf \
        --restart=always \
        -p 80:80 \
        --link querymail-php \
        -d nginx \
        nginx -c /nginx.conf