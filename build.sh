#!/usr/bin/env bash
set -ex

# Start a new, empty project
rm -rf build
mkdir build
composer create-project --no-install --no-scripts laravel/laravel build

cd build

# use local .env file
ln -s  ../.env ./.env

# Add the local package via a path
composer config repositories.dmlogic/community-contributions path ../
composer install
composer require dmlogic/community-contributions

# Ready for post install scripts
composer run-script post-root-package-install
composer run-script post-create-project-cmd

# Create a database and use support files from the package
touch database/database.sqlite
rm -fr database/factories
rm -fr database/migrations
rm -fr database/seeders
ln -s ../../src/database/factories ./database/factories
ln -s ../../src/database/migrations ./database/migrations
ln -s ../../src/database/seeders ./database/seeders

# Symlink to public files
ln -s ../../public ./public/community

# Migrate database
php artisan migrate:fresh --seed
