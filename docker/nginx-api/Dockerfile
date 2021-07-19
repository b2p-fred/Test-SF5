ARG TAG_NGINX=1.20

FROM nginx:${TAG_NGINX}

# Copy the different available configurations
COPY ./docker/nginx-api/build/default.conf /etc/nginx/sites-available/default.conf
# Define several possible configuration and update the docker-entrypoint script to apply on start !
#COPY ./docker/nginx-api/build/default_dev.conf /etc/nginx/sites-available/default_dev.conf
#COPY ./docker/nginx-api/build/default_prod.conf /etc/nginx/sites-available/default_prod.conf

# Container entrypoint
COPY ./docker/nginx-api/build/docker-entrypoint /

#COPY ./src_api /srv

RUN set -eux \
    # Remove the default installed configuration
#    && rm /etc/nginx/nginx.conf \
    && rm /etc/nginx/conf.d/default.conf \
    # Update nginx-api user
    && usermod -u 1000 www-data

# Configure entrypoint
ENTRYPOINT [ "/docker-entrypoint" ]