#!/bin/sh
vagrant ssh -c "cd /vagrant/docker/run; docker compose logs -f"
