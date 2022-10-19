#!/bin/bash

#change to the directory of the script
cd "$( dirname "${BASH_SOURCE[0]}" )"

# this will get the container suffix from the first parameter and set it to "fpm" if no value was provided
CONTAINER=${1}
: ${CONTAINER:=fpm}

cd run/ && docker exec -i -t ${CONTAINER} /bin/bash
