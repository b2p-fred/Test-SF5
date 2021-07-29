ARG TAG_NODEJS=14
ARG TAG_NGINX=1.20
ARG VERSION=beta
ARG UID=1000
ARG GID=1000

# -----
# Using the Node base image
FROM node:${TAG_NODEJS}-slim AS node

LABEL com.description="eProtocole Web client application - development"
LABEL com.version="${VERSION}"
LABEL com.authors="B2P Web"

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

# Current user identifier & application directory ownership
# For alpine images:
# RUN deluser www-data && adduser -DH -h /home/www-data -s /sbin/nologin -u 1000 www-data
RUN usermod -u 1000 $NODE_USER &&\
    groupmod -g 1000 $NODE_USER &&\
    usermod -a -G $NODE_USER root &&\

    mkdir -p /app &&\
    chown -R "$NODE_USER:$NODE_USER" /app

WORKDIR /app

COPY --chown="$NODE_USER:$NODE_USER" my-web-front .

RUN set -eux &&\
    # Install Vue-js cli
    yarn global add @vue/cli &&\
    # Install application dependencies
    yarn install --non-interactive --no-progress

USER ${NODE_USER}

RUN set -eux &&\
    # Build for production
    yarn build --verbose

COPY ./docker/frontend/build/node_docker-entrypoint.sh /

ENTRYPOINT [ "/node_docker-entrypoint.sh" ]
CMD [ "yarn", "serve" ]

# -----
# For production environment
# -----
FROM nginx:${TAG_NGINX}-alpine as nginx

LABEL com.description="eProtocole Web client application - production"
LABEL com.version="${VERSION}"
LABEL com.authors="B2P Web"

EXPOSE 80/tcp

# Copy the different available configurations
COPY ./docker/frontend/build/default.conf /etc/nginx/conf.d/
COPY ./docker/frontend/build/gzip.conf /etc/nginx/conf.d/

# Copy the built application from the node container
COPY --from=node /app/dist /usr/share/nginx/html
