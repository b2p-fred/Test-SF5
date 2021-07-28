ARG TAG_NODEJS=14

# -----
# Using the Node base image
FROM node:${TAG_NODEJS}-slim AS node

# install dumb-init and gosu
ARG DUMB_INIT_VERSION=1.2.0
ARG GOSU_VERSION=1.10
RUN set -xe \
    && apt update \
    && apt install --no-install-recommends --yes \
        ca-certificates \
        curl \
    && curl --location --output "/usr/local/sbin/dumb-init" \
        "https://github.com/Yelp/dumb-init/releases/download/v${DUMB_INIT_VERSION}/dumb-init_${DUMB_INIT_VERSION}_amd64" \
    && chmod +x "/usr/local/sbin/dumb-init" \
    && curl --location --output "/usr/local/sbin/gosu" \
        "https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-amd64" \
    && chmod +sx "/usr/local/sbin/gosu" \
    && apt-mark auto '.*' > /dev/null \
    && apt purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false
# -----

ENV \
    NODE_ENV=development \
    NODE_USER=node

# Application directory
RUN usermod -u 1000 node
RUN groupmod -g 1000 node
RUN usermod -a -G node root

RUN mkdir -p /srv/app
RUN mkdir -p /srv/app/.yarn-root-cache
RUN chown -R "$NODE_USER":"$NODE_USER" /srv/app
COPY --chown="$NODE_USER":"$NODE_USER" my-web-front /srv/app

WORKDIR /srv/app

RUN set -eux &&\
    # Set the yarn default cache location
    yarn config set cache-folder /srv/app/.yarn-root-cache &&\
    # Install application dependencies
    yarn install --non-interactive --no-progress &&\
    # Install Vue-js cli
    yarn global add @vue/cli

COPY ./docker/nodejs/build/node_docker-entrypoint.sh /

RUN set -eux &&\
    # Build for production
    yarn build --verbose

USER node

ENTRYPOINT [ "/usr/local/sbin/dumb-init", "--", "/node_docker-entrypoint.sh" ]
CMD [ "yarn", "serve" ]
#CMD [ "tail", "-f", "/dev/null" ]

# -----
# For production environment
# -----
FROM nginx:stable-alpine as nginx

ENV \
    API_ENDPOINT="http://localhost" \
    WS_ENDPOINT="http://localhost:81" \
    BRANDING="default" \
    TITLE="apTeleCare"

LABEL com.description="eProtocole Web client application"
LABEL com.version="${VERSION}"
LABEL com.authors="B2P Web"

EXPOSE 80/tcp

# Copy the different available configurations
COPY ./build/gzip.conf /etc/nginx/conf.d/
COPY ./build/default.conf /etc/nginx/sites-available/default.conf

# Copy the built application from the node container
COPY --from=node /srv/app/dist /srv/app/dist
WORKDIR /srv/app

RUN set -eux \
    # Prepare system
    && apk add --no-cache --no-progress python2 \
    # Configure nginx
    && rm /etc/nginx/conf.d/default.conf \
    # Clean
    && rm -f -r \
           /root/.npm \
           /tmp/*

ENTRYPOINT [ "/docker-entrypoint" ]
CMD [ "nginx", "-g", "daemon off;" ]
