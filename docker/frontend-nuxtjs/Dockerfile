ARG TAG_NODEJS=14
ARG TAG_NGINX=1.20
ARG VERSION=beta
#ARG UID=1000
#ARG GID=1000

# -----
# Using the Node base image
FROM node:${TAG_NODEJS}-slim AS node

LABEL com.description="eProtocole Web client application - development"
LABEL com.version="${VERSION}"
LABEL com.authors="B2P Web"

EXPOSE 3000/tcp

ENV \
    NODE_ENV=development \
    NODE_USER=node \
    HOST=0.0.0.0

# Current user identifier & application directory ownership
# For alpine images:
# RUN deluser www-data && adduser -DH -h /home/www-data -s /sbin/nologin -u 1000 www-data
RUN usermod -u 1000 $NODE_USER &&\
    groupmod -g 1000 $NODE_USER &&\
    usermod -a -G $NODE_USER root &&\
    mkdir -p /app &&\
    chown -R "$NODE_USER:$NODE_USER" /app

COPY my-nuxt-front/package.json /app/package.json
COPY my-nuxt-front/yarn.lock /app/yarn.lock

WORKDIR /app

# Install application dependencies
RUN set -eux && yarn install --non-interactive

COPY --chown="$NODE_USER:$NODE_USER" my-nuxt-front .

# Build for production
RUN set -eux && yarn generate

USER ${NODE_USER}

COPY ./docker/frontend-nuxtjs/build/node_docker-entrypoint.sh /

ENTRYPOINT [ "/node_docker-entrypoint.sh" ]
CMD [ "yarn", "dev" ]

# -----
# For production environment
# -----
FROM nginx:${TAG_NGINX}-alpine as nginx

LABEL com.description="eProtocole Web client application - production"
LABEL com.version="${VERSION}"
LABEL com.authors="B2P Web"

EXPOSE 80/tcp

# Copy the different available configurations
COPY ./docker/frontend-nuxtjs/build/default.conf /etc/nginx/conf.d/
COPY ./docker/frontend-nuxtjs/build/gzip.conf /etc/nginx/conf.d/

# Copy the built application from the node container
#COPY --from=node /app/build /usr/share/nginx/html
COPY --from=node /app/dist /usr/share/nginx/html
