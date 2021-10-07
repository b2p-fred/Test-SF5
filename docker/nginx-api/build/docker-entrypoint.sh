#!/bin/sh
set -eu
echo "Docker entrypoint for nginx (hostname: ${HOSTNAME})..."
cp /etc/nginx/sites-available/default.conf /etc/nginx/conf.d/default.conf

nginx -g "daemon off;"
