# My SF5 tests project

![CI setup](https://github.com/b2p-fred/Test-SF5/actions/workflows/1-setup_tests.yml/badge.svg)

[![Code Coverage](https://codecov.io/gh/b2p-fred/Test-SF5/branch/develop/graph/badge.svg)](https://codecov.io/gh/b2p-fred/Test-SF5)

## Des outils

Accéder à l'API en ligne de commande :
```shell
$ cd bin
$ ./api-example.sh -h
```

## De la documentation

Plusieurs sujets : 
- [process](doc/dev_process.md) et [règles de développement](doc/dev_rules.md)
- outils (installation et configuration): [Docker](doc/tool_Docker.md), [PHP Storm](doc/tool_PHPStorm.md)
- ...

À voir : tous les fichiers [dans le répertoire *doc*](doc)

## A makefile to rule them all... 

To run all the services:
```shell
# Build all the Docker containers
$ make build

# Run the services
# - in dev environment 
$ make up
# - in test environment 
$ make up-test

# Create the database and load all the tests fixtures
$ make db

# Check coding standard
$ make cs

# Execute unit tests
$ make tests

# Execute Behat tests
$ make behat-tests
```
