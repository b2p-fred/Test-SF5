#!/bin/sh
set -eu

echo "Docker entrypoint for PHP-FPM (hostname: ${HOSTNAME})..."

# Configure application
if [ "${APP_ENV}" = "prod" ]; then
  # Install composer packages for production
  echo "Installing project dependencies for production"
  until composer install --classmap-authoritative --no-dev --no-interaction --no-progress --optimize-autoloader --prefer-dist; do
    sleep 5
  done

  # todo: to be completed with application build and deployment ?
else
  # Install composer packages
  echo "Installing project dependencies in dev mode..."
  until composer install --no-interaction --no-progress --prefer-dist; do
    sleep 5
  done

  # Run PHP FPM in daemon mode
  php-fpm --daemonize

  # Remove Symfony server PID (avoid re-using older stuff!)
  [ -f .symfony/var/*.pid ] && { echo "Removing an existing .symfony/var/*.pid file."; rm .symfony/var/*.pid; }

#  # Starting application content server
#  symfony --no-tls --allow-http server:start --daemon

  # Generate SSL keys for the JWT Token (if they do not exist)
  [ ! -f config/jwt/private.pem ] && { echo "Generating JWT token keys..."; symfony console lexik:jwt:generate-keypair; }
fi

echo "Executing $@"
"$@"
