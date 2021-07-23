# Environnement de développement
## Installation

Disposer d'un PC avec une distribution Linux (eg. Linux Mint - [voir ici](./Linux_Mint.md)) et les principaux outils de développement usuels. 

Installer PHP Storm ([voir ici](tool_PHPStorm.md)).

Installer Docker ([voir ici](tool_Docker.md)).

Configurer pour utiliser Github avec une clef SSH:
```shell
# More information in the Github docs
# ---
# Create an SSH key
$ ssh-keygen -t ed25519 -C "glagaffe@b2pweb.com"

# Add the private key to the SSH local agent
$ eval "$(ssh-agent -s)"
$ ssh-add ~/.ssh/id_ed25519

# Get the public key
$ cat ~/.ssh/id_ed25519.pub 
# Add this text to a new Github SSH key:
ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIPXd7iC2l9+e/xaZsLnpac1l20htlV6+WgWpYL923iUi glagaffe@b2pweb.com
```

Récupérer le repository de développement (eg. *b2pweb/sandbox*)
```shell
# Note there is no https in the URL thanks to the SSH connection
$ git clone git@github.com:b2pweb/sandbox.git

# To the develop branch rather than main
$ git checkout develop
```

Configurer le shell :
```shell
# Setup the local Docker & Symfony environment
$ cp .env.dist .env
# ---
# Symfony uses .env, Docker also uses .env ... mixing both is touchy -)
# Symfony recommends to include the .env file in the repository but it is a bad practise!
```

**Note :** Il ne faut pas modifier le fichier `.env` mais plutôt créer un fichier `.env.local` (ou autre `.env.dev.local`) comme [préconisé par Symfony](https://symfony.com/doc/current/configuration.html#configuring-environment-variables-in-env-files).

Lancer les services Docker :
```shell
# Build and start the Docker environment
$ docker-compose build
$ docker-compose up

# browse:
# `http://localhost:8080` for the PHPMyadmin Web interface
# `http://localhost:8000` or `http://localhost:8000/lucky/number` for the API interface (Symfony 5)
```

## Some Docker stuff
```shell
# Start the Docker environment with console output
$ docker-compose up

# Start the Docker environment in daemon mode
$ docker-compose up -d
# Get the logs 
$ docker-compose logs

# Stop the running services
$ docker-compose down
 
# and remove all the share volumes
$ docker-compose down --volumes

```

Stop all the running containers:
```shell
$ docker stop $(docker ps -a -q)
```

Clear all the Docker local stuff (images, containers, ...):
```shell
$ docker system prune --all
```
