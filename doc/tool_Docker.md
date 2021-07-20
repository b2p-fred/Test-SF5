# Docker

## Installation 

Abstract from the official Docker documentation:
```
    # Cleaning
    sudo apt remove docker docker-engine docker.io containerd runc
    sudo apt update

    # To force use HTTPS...
    sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg-agent \
    software-properties-common

    # Install the official Docker repositories GPG key
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    sudo apt-key fingerprint 0EBFCD88
    sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable"
    sudo apt update

    # Installing
    sudo apt install docker-ce docker-ce-cli containerd.io

    # Checking...
    docker -v

    sudo docker run hello-world
    # ... read what the console displays!
```

Using docker as a non-root user:
```
    # Create a docker group
    sudo groupadd docker
    
    # Join the newly created group
    newgrp docker

    # Add yourself to the group
    sudo usermod -aG docker $USER

    # Checking...
    docker run hello-world
```

Installation of ` docker-compose`:
```
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

```

## Development

**Note** that this is only necessary if the user identity is defined in a common repository where each user has its own id ... on our current PC configuration, all users are identified as 1000 -)

Because all the development tools are based on Docker containers, it is important to share the user identity between the host computer and the Docker containers. The main reason is to manage correctly the shared volumes files permissions. The user identity is locally stored in an environment variable that must be exposed in the shell environment.

```sh

# Push _UID variable in your environment

echo "export _UID=$(id -u)" >>  ~/.bashrc
```
**Note** : you need to log-out / log-in for this variable to get correctly installed in your shell -)

**Note** if some files shared between the host and Docker containers are root:root owned, it may be because this user identifier is not correctly set!


