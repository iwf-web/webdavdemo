#!/usr/bin/env bash

# This file contains instructions to setup the local development environment inside the vagrant box
# typically this file should only specifiy non-standard tasks to setup the build
#
# This script is run as user vagrant.

echo "Running additional project specific tasks to setup system for development. Please wait..."

#fix permission problem with creating directory in docker-compose (directories are created as root under windows)
mkdir -p "/vagrant/vendor"

# fix permission for data directory
mkdir -p ~/appdata/app/var/cache ~/appdata/app/var/logs ~/appdata/app/var/sessions ~/appdata/app/data ~/appdata/app/web
sudo chown -R vagrant:vagrant ~/appdata

echo "... finished."
