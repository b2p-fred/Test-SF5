# My SF5 tests project

![Symfony workflow](https://github.com/b2p-fred/Test-SF5/actions/workflows/symfony.yml/badge.svg)

[![Code Coverage](https://codecov.io/gh/b2p-fred/Test-SF5/branch/develop/graph/badge.svg)](https://codecov.io/gh/b2p-fred/Test-SF5)

## Des outils

Accéder à l'API en ligne de commande :
```shell
$ cd bin
$ ./api-example.sh -h
```

## De la documentation

Plusieurs sujets : 
- [process](./doc/dev_process.md) et [règles de développement](doc/dev_rules.md)
- outils (installation et configuration): [Docker](./doc/tool_Docker.md), [PHP Storm](./doc/tool_PHPStorm.md)
- ...

A voir : tous les fichiers [dans le répertoire *doc*](./doc)

## Common files

`.gitignore` to avoid including anything in the repo
`.dockerignore` to avoid including anything in the Docker layers

## Docker files

**Note:** before running any Docker command, please copy the *.env.dist* provided file to *.env* and feel free to adapt this file content to your host needs -)

Symfony allows using a *.env.local*

`.dockerignore` to avoid including some files in the Docker layers
`docker-compose.yml` the main file to run all the services
`./docker` all the docker stuff used in the configuration

In PHPStorm, menu File / Settings / Build, create a new Docker configuration named *My Docker** and configured to be used with a Unix socket.

The services' configuration is located in the *.env* file:
- software components version (eg. nginx, MariaDB, ...)
- application versions
- database parameters
- application parameters and configuration

To run all the services:
```shell
# Check if some containers are running
$ docker ps -a
$ docker-compose down

# Run this command (to tail the containers log)
$ docker-compose up

# Or this one (Ctrl+C will not stop the containers)
$ docker-compose up -d && docker-compose logs -f
```

To prepare the application:
```shell
# Create the database
$ docker exec -it docker_sf5_php-fpm symfony console doctrine:database:create

# Populate the migrations
$ docker exec -it docker_sf5_php-fpm symfony console doctrine:migrations:migrate

# Load the test fixtures
$ docker exec -it docker_sf5_php-fpm symfony console doctrine:fixtures:load
```

Then browse:
- `http://localhost:8080` for the PHPMyadmin Web interface
- `http://localhost:8000` for the API interface (Symfony 5)
- `http://localhost:8002` for the front end application

**Tip** while browsing `http://localhost:8000/lucky/number`, have a look to the Docker log -)

The Docker configuration starts PHP-FPM and Nginx containers to allow executing the Symfony application of the project (source files located in the *./src* folder). Also, it starts a second Nginx instance to serve the *./src_ui* Html files.

**Note** the front end part of the application is not yet available in this repository...

To stop all the services:
```shell
# Ctrl+C if you started with: docker-compose up

# Else
$ docker-compose down
```
