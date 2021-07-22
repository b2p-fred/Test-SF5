ARG TAG_PHP=7.4-fpm

FROM php:${TAG_PHP}

EXPOSE 9000/tcp

ENV \
    COMPOSER_ALLOW_SUPERUSER=1 \
    APP_ENV=prod \
    APP_USER=www-data

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip \
        zlib1g-dev \
        libxml2-dev \
        libpng-dev \
        libzip-dev \
        curl debconf apt-transport-https apt-utils \
        build-essential locales acl \
    && docker-php-ext-install \
        pdo_mysql \
        soap \
        zip \
        opcache \
        gd \
        intl
    #
    # composer
    #
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
# Intentionnaly install a specific version !
    && php composer-setup.php --version=2.1.3 \
# Uncomment to use the last stable version
#    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

#    # Install phpcov tool
#    && curl --location --output "vendor/bin/phpcov" "https://phar.phpunit.de/phpcov-7.0.2.phar" \
#    && chmod +x "vendor/bin/phpcov" \
#
#    #
#    # Symfony
#    #
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    &&  mv /root/.symfony/bin/symfony /usr/local/bin

# Use the default production configuration
#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# Use the default development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY build/symfony.ini "$PHP_INI_DIR/conf.d/symfony.ini"

# Application directory
RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
RUN usermod -a -G www-data root

RUN mkdir -p /var/www
RUN chown -R www-data:www-data /var/www
RUN mkdir -p /var/www/.composer
RUN chown -R www-data:www-data /var/www/.composer

WORKDIR /var/www/

USER www-data

#CMD composer install ; php bin/console doctrine:migrations:migrate ; php-fpm --nodaemonize
CMD composer install ; php-fpm --daemonize ; symfony server:start

#CMD ["php-fpm", "--nodaemonize"]
## Container entrypoint
#COPY build/docker-entrypoint.sh /
#
## Configure entrypoint
#ENTRYPOINT [ "/docker-entrypoint.sh" ]