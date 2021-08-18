#!/bin/bash
#
# Most parts of this script are copied from the MariaDB initialization script used in the Docker image
# Each string DB listed in the MARIADB_EXTRA_DATABASES env variable makes a new database DB and a new user
# DB created with the password DB. The new user and the main user MARIADB_USER are granted all privileges
# on the new database.
#

# Execute the client, use via docker_process_sql to handle root password
docker_exec_client() {
        # args sent in can override this db, since they will be later in the command
        if [ -n "$MYSQL_DATABASE" ]; then
                set -- --database="$MYSQL_DATABASE" "$@"
        fi
        mysql --protocol=socket -uroot -hlocalhost "$@"
}

# Execute sql script, passed via stdin
# usage: docker_process_sql [--dont-use-mysql-root-password] [mysql-cli-args]
#    ie: docker_process_sql --database=mydb <<<'INSERT ...'
#    ie: docker_process_sql --dont-use-mysql-root-password --database=mydb <my-file.sql
docker_process_sql() {
        passfileArgs=()
        if [ '--dont-use-mysql-root-password' = "$1" ]; then
                shift
                MYSQL_PWD= docker_exec_client "$@"
        else
                MYSQL_PWD=$MARIADB_ROOT_PASSWORD docker_exec_client "$@"
        fi
}

if [[ -n $MARIADB_EXTRA_DATABASES ]]
then
    set -e
    set -u

    function create_user_and_database() {
        local database=$1

        echo " - Creating database '$database'"
        docker_process_sql --database=mysql <<<"CREATE DATABASE IF NOT EXISTS \`$database\`;"
        echo " - Creating user '$database'"
        docker_process_sql --database=mysql <<<"GRANT ALL ON \`$database\`.* TO '$database'@'%' IDENTIFIED BY '$database';"

        if [ -n "$MARIADB_USER" ] && [ -n "$MARIADB_PASSWORD" ]; then
            echo " - Giving user '${MARIADB_USER}' access to schema '$database'"
            docker_process_sql --database=mysql <<<"GRANT ALL ON \`$database\`.* TO '$MARIADB_USER'@'%' ;"
        fi
    }

    if [ -n "$MARIADB_EXTRA_DATABASES" ]; then
        echo "Multiple database creation requested: $MARIADB_EXTRA_DATABASES"
        for db in $(echo $MARIADB_EXTRA_DATABASES | tr ',' ' '); do
            create_user_and_database $db
        done
        echo "Multiple databases created."
    fi
fi