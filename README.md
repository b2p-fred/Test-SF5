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

In PHPStorm, menu File / Settings / Build, create a new Docker configuration named *ùMy Docker** and configured to be used with a Unix socket.


