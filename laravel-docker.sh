#!/bin/bash

# This script allows you to run Laravel Artisan commands from outside the Docker container.

docker-compose exec app php artisan "$@"