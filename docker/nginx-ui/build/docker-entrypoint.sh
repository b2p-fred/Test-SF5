#!/bin/sh
set -eu
echo "Configuring nginx (hostname: ${HOSTNAME})..."
cp /etc/nginx/sites-available/default.conf /etc/nginx/conf.d/default.conf
echo "Done!"

nginx -g "daemon off;"
