#!/bin/sh
set -eux

echo "Configuring PHP (hostname: ${HOSTNAME})..."

# Do not care with this for the moment -)
# Create user if necessary
if ! getent passwd "${APP_USER}"; then
    useradd --uid "${APP_USER}" --create-home dev
fi

## Use APP_USER as php-fpm user and fix permissions
#sed --expression "s/www-data/${APP_USER}/g" --in-place /etc/php/*/fpm/php-fpm.d/docker.conf
#chown -R "${APP_USER}:${APP_USER}" var public
#

# Configure application
if [ "${APP_ENV}" = "prod" ]; then
    # Generate cache
    until composer install --classmap-authoritative --no-dev --no-interaction --no-progress --optimize-autoloader --prefer-dist ; do
        sleep 5
    done
else
    # Generate cache
    until composer install --no-interaction --no-progress --prefer-dist ; do
        sleep 5
    done
fi

echo "Done!"

# Run Symfony server
symfony server:start
