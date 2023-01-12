#!/usr/bin/env bash
set -ex
mkdir build
composer create-project --no-install --no-scripts laravel/laravel build

cd build

cp ../.env ./.env

composer config repositories.dmlogic/community-contributions path ../
composer install
composer require dmlogic/community-contributions
composer run-script post-root-package-install
composer run-script post-create-project-cmd

rm database/migrations/*.php
touch database/database.sqlite
php artisan migrate
