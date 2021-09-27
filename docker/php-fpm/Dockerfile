ARG TAG_PHP=7.4-fpm

FROM php:${TAG_PHP}

# Default identifiers to use for user substitution
ARG _UID=1000
ARG _GID=1000

EXPOSE 9000/tcp

ENV \
    _UID=$_UID \
    _GID=$_GID \
    PHP_USER=www-data \
    COMPOSER_ALLOW_SUPERUSER=1 \
    APP_ENV=prod \
    APP_USER=www-data

    #
    # Base tools, php and extensions
    #
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libmagickwand-dev \
        ghostscript \
        git unzip \
        zlib1g-dev \
        libxml2-dev \
        libpng-dev \
        libzip-dev \
        libonig-dev \
        curl debconf apt-transport-https apt-utils \
        build-essential locales acl \
    && docker-php-ext-install \
        bcmath \
        gd \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        zip

RUN pecl install pcov imagick && \
    docker-php-ext-enable pcov imagick

    #
    # composer
    #
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    # Intentionally install a specific version !
    && php composer-setup.php --version=2.1.3 \
    # Uncomment to use the last stable version
    #    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

    #
    # Symfony
    #
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    &&  mv /root/.symfony/bin/symfony /usr/local/bin

# Use the default production configuration
#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# Use the default development configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY build/symfony.ini "$PHP_INI_DIR/conf.d/symfony.ini"

ARG imagemagic_config=/etc/ImageMagick-6/policy.xml
RUN if [ -f $imagemagic_config ] ; then sed -i 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read|write" pattern="PDF" \/>/g' $imagemagic_config ; else echo did not see file $imagemagic_config ; fi

RUN echo "*****" &&\
    echo "Application user id: ${PHP_USER} ${_UID} ${_GID}" &&\
    echo "*****" &&\

    # Sustitute identifiers for the PHP user &&\
    usermod -u $_UID $PHP_USER &&\
    groupmod -g $_GID $PHP_USER &&\
    usermod -a -G $PHP_USER root &&\

    # Application directory &&\
    mkdir -p /var/www &&\
    mkdir -p /var/www/.composer &&\
    chown -R "$PHP_USER:$PHP_USER" /var/www

# Container entrypoint
COPY ./build/docker-entrypoint.sh /

# Install application
COPY . /var/www
WORKDIR /var/www/

#USER $PHP_USER

# Configure entrypoint
ENTRYPOINT [ "/docker-entrypoint.sh" ]

CMD ["tail", "-f", "/dev/null"]
