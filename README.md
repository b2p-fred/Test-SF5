# My SF5 tests project

## De la documentation

Plusieurs sujets : 
- [process](./doc/dev_process.md) et [règles de développement](doc/dev_rules.md)
- outils (installation et configuration): [Docker](./doc/tool_Docker.md), [PHP Storm](./doc/tool_PHPStorm.md)
- ...

A voir tous les fichiers [dans le répertoire *doc*](./doc/README.md)

## Common files

`.gitignore` to avoid including anything in the repo

## Docker files

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
# Run this command (to tail the containers log)
docker-compose up
# Or this one (Ctrl+C will not stop the containers)
docker-compose up -d && docker-compose logs -f
```

Then browse:
- http://localhost:8080 for the PHPMyadmin Web interface
- http://localhost:8001 for the API interface
- http://localhost:8002 for the front end application

The Docker configuration starts PHP-FPM and Nginx containers to allow executing the PHP scripts located in the *./src_api* folder. Also it starts a second Nginx instance to serve the *./src_ui* Html files.

To stop all the services:
```shell
# Ctrl+C if you started with: docker-compose up

# Else
docker-compose down
```

