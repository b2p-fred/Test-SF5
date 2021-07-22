ARG TAG_NGINX=1.20

FROM nginx:${TAG_NGINX}

LABEL com.description="eProtocole Web application"
LABEL com.version="${VERSION}"
LABEL com.authors="B2P Web"

EXPOSE 80/tcp

# Copy the different available configurations
COPY ./build/default.conf /etc/nginx/sites-available/default.conf
# Define several possible configuration and update the docker-entrypoint.sh script to apply on start !
#COPY ./build/default_dev.conf /etc/nginx/sites-available/default_dev.conf
#COPY ./build/default_prod.conf /etc/nginx/sites-available/default_prod.conf

# Container entrypoint
COPY ./build/docker-entrypoint.sh /

RUN set -eux \
    # Remove the default installed configuration
    && rm /etc/nginx/conf.d/default.conf \
    # Update nginx default  user
    && usermod -u 1000 www-data

# Configure entrypoint
ENTRYPOINT [ "/docker-entrypoint.sh" ]