#!/bin/sh

# Install/update dependencies
echo "Node user id: ${NODE_USER}"
# Set the yarn default cache location
yarn config set cache-folder /tmp/.yarn-user-cache
# Install application dependencies and build for production
gosu "${NODE_USER}:${NODE_USER}" yarn install --non-interactive --no-progress
gosu "${NODE_USER}:${NODE_USER}" yarn build

exec gosu "${NODE_USER}:${NODE_USER}" "$@"