#!/bin/sh
docker run --rm --network host -v /vagrant:/usr/src/app -w /usr/src/app node yarn install --silent
docker run --rm --network host -v /vagrant:/usr/src/app -w /usr/src/app node yarn encore dev
