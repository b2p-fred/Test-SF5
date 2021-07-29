#!/bin/sh

# Install/update dependencies
echo "Node user id: ${NODE_USER}, current user is: $(id -u -n) ($(id -u))"
# Install application dependencies and build for production
yarn install --non-interactive --no-progress
yarn build

"$@"