ARG TAG_MARIADB=10.5.11

FROM mariadb:${TAG_MARIADB}

COPY build/* /docker-entrypoint-initdb.d/

EXPOSE 3306/tcp
