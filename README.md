# TLDR

Repo holding the code for Tad's linkme-biz-interview

## Reference links

- https://blog.devsense.com/2022/develop-php-in-docker

## Check container OS details

- cat /etc/os-release

## Running the php server

- php -S 0.0.0.0:8000 -t public

## Print location of php.ini file

- php --ini
  - Configuration File (php.ini) Path: /usr/local/etc/php

## installing mysql

- uncommented line #950 in `/usr/local/etc/php/php.ini-production` and restarted php server

## Running development containers with Docker Compose

- docker-compose -f docker-compose.yml up

## Running in linkme network

- docker network create linkme