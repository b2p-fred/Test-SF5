# Outils de développement
## Utilities
 Les outils CLI de Vue.js utilisent git s'il est installé localement, donc il ne faut pas se priver. Curl est aussi un must-have...

    sudo apt install curl git


## Node + NPM

Utiliser une image Docker avec node.js et yarn pré-installés.

## Installation de Vue CLI (optionnel... mais indispensable !)
 
 Vue-cli est un outil en ligne de commande qui permet de créer et éditer une application Vue.js.

```shell
    $ yarn global install @vue/cli
	......
	added 843 packages from 556 contributors in 73.515s

    $ vue --version
	@vue/cli 4.5.13
```

# Création d'un projet Vue.js

## Utilisation de vue CLI

Voir ici: https://cli.vuejs.org/guide/creating-a-project.html#vue-create

```shell
    $ vue create my-web-front
	# Sélectionner Vue(v2) + babel + ESLint/Prettier
	# Sélectionner toutes les features (PWA, unit test, ...)
	# Sélectionner Jest et Nightwatch (Chrome et Firefox)
	# Configuration in package.json
	
	# Voir ici: https://cli.vuejs.org/guide/mode-and-env.html#environment-variables
	# Créer un fichier .env qui contient :
	# BASE_URL=/
	
	$ cd my-web-front
	$ vue-cli-service serve
```

## Test en développement
 
```shell
	$ vue-cli-service serve
    # http://localhost:8080
```

## Déploiement
 
```shell
	$ vue-cli-service build
```

 Le contenu du dossier *.dist* est à placer dans un serveur Web pour accès distant.

**Attention** : Le fichier `.htaccess` (apache) doit contenir les lignes suivantes :

     # SPA: Single Page Application: Redirect to /
     RewriteEngine On 
     Redirect permanent "/login" "/"
     Redirect permanent "/lost_password" "/"
     # Redirect permanent "/xxxxx"/"


## Test du déploiement en local

Ceci n'a d'intérêt que pour déployer l'application dans un serveur Nginx installé en local. Sinon, utiliser l'image Docker -)

```shell
    $ sudo apt install nginx
    $ service system status
    ● nginx.service - A high performance web server and a reverse proxy server

    $ http://localhost
    Welcome to nginx!

    $ sudo touch /etc/nginx/sites-available/pwa_portal
    $ sudo ln -s /etc/nginx/sites-available/pwa_portal /etc/nginx/sites-enabled/pwa_portal

    $ sudo vi /etc/nginx/sites-available/pwa_portal
        # http://localhost:8081
        # root = /home/fred/pwa-portal/dist
        server {
            listen      8081;
            server_name localhost;
            charset     utf-8;
            root        /home/fred/pwa-portal/dist;
            index       index.html index.htm;
            # Always serve index.html for any request
            location / {
                root /home/fred/pwa-portal/dist;
                try_files $uri /index.html;
            }
            error_log  /var/log/nginx/vue-app-error.log;
            access_log /var/log/nginx/vue-app-access.log;
        }
    
```

