#!/bin/bash
#set -x

echo "Building base image (local)"
docker build \
  --no-cache \
  --pull \
  -f Dockerfile.base \
  --force-rm \
  -t local/app-base:latest \
  .
