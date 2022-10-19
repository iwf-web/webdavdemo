#!/usr/bin/env bash

# add your crontabs to data/conf/crontabs directory
CRONTAB=/data/conf/crontabs/crontab.${RUNTIME_ENVIRONMENT}

# check if file exists, then sym-link it
if [ -e ${CRONTAB} ]; then

    crontab ${CRONTAB}
    echo "$(date): Installed crontab file ${CRONTAB}."
else
    echo "$(date): No crontab found."
    exit 1
fi
