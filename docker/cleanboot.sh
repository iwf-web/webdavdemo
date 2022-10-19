#!/bin/bash

#this script tears all docker containers from the compose stack down, removes all the logs and starts the stack again

#change to the directory of the script
cd "$( dirname "${BASH_SOURCE[0]}" )"

#remove everything
cd run/ && docker compose down
echo "---------"

#clear all the data
rm -rf data/app-data/cache/dev &&
 rm -rf data/app-data/cache/prod &&
 rm -rf data/app-data/data/media/* &&
 rm -rf data/app-data/logs &&
 rm -rf data/app-data/mysql &&
 rm -rf data/app-data/sessions

#setup everything & print the logs
docker compose up -d && docker compose ps && docker compose logs
