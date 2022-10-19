#!/bin/bash
#set -x

cd ../build
sh base_image.sh

cd ../build-local
docker build -t local/app:latest  .
