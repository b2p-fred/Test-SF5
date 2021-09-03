#!make
# Specify a shell
SHELL := /bin/bash

# Create the .env file if it does not exist
ifeq (,$(wildcard .env))
   $(shell cp .env.dist .env)
endif
# Get the environment variables
include .env

# Create the .env file if it does not exist
ifeq (,$(wildcard docker-compose.override.yml))
   $(shell cp docker-compose.override.yml.dist docker-compose.override.yml)
endif

# Color strings
GREY = /bin/echo -e "\x1b[30m$(1)\x1b[0m"
RED = /bin/echo -e "\x1b[31m$(1)\x1b[0m"
GREEN = /bin/echo -e "\x1b[32m$(1)\x1b[0m"
YELLOW = /bin/echo -e "\x1b[33m$1\x1b[0m"
BLUE = /bin/echo -e "\x1b[34m$(1)\x1b[0m"
PURPLE = /bin/echo -e "\x1b[35m$(1)\x1b[0m"
CYAN = /bin/echo -e "\x1b[36m$(1)\x1b[0m"
WHITE = /bin/echo -e "\x1b[37m$(1)\x1b[0m"

# Project version string
PROJECT_VERSION_STRING = Project: $(PROJECT), version $(PROJECT_VERSION)

# Alias
SYMFONY         = docker exec $(CONTAINER_NAME) symfony
SYMFONY_CMD     = $(SYMFONY) console
COMPOSER        = docker exec $(CONTAINER_NAME) composer

# Misc
.DEFAULT_GOAL = info
                # Not needed here, but you can put your all your targets to be sure
                # there is no name conflict between your files and your targets.
.PHONY        = banner info help

# Commands and information
info: commands
	@echo "------------------------------"
	@$(call YELLOW, "How to use commands:")
	@echo "------------------------------"
	@$(call YELLOW, "First install ?")
	@echo "     - build: build (re-build) the Docker images and containers $(CONTAINER_NAME)"
	@echo "     - clean: remove Docker containers and images"
	@echo "     ---"
	@$(call YELLOW, "Start / stop project services ?")
	@echo "     - up: start the project services in console mode"
	@echo "     - up-detach: start the project services in daemon mode"
	@echo "     - down: stop the project services"
	@echo "     ---"
	@$(call YELLOW, "Some Symfony useful commands !")
	@echo "     - sf: list all available commands"
	@echo "     - cc: clear the cache"
	@echo "     - wc: warmup the cache"
	@echo "     - purge: purge the cache and log files"
	@echo "     ---"
	@$(call YELLOW, "For the database")
	@echo "     - db: full update of the database (schema + data)"
	@echo "     ---"
	@$(call YELLOW, "Coding standards and tests")
	@echo "     - cs: runs lint and phpstan"
	@echo "     - tests: runs all the tests (else use unit-tests, api-tests or app-tests)"


# --------------------
commands: banner
	@echo -e "$$(grep -hE '^\S+:.*##' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' -e 's/^\(.\+\):\(.*\)/\\x1b[36m\1\\x1b[m:\2/' | column -c2 -t -s :)"


# --------------------
banner: ## The project information string (project name and version)
	@$(call CYAN, "------------------------------------------------------------")
	@$(call CYAN, $(PROJECT_VERSION_STRING))
	@$(call CYAN, "------------------------------------------------------------")


## —— Docker 🐳 ————————————————————————————————————————————————————————————————
clean: ## Remove project Docker containers, images, network, volume
	docker rm $(CONTAINER_NAME)
	docker rmi ${IMAGE_NAME}


clean-yarn-cache: ## Clean Yarn global cache
	docker exec -t -i $(CONTAINER_NAME) yarn cache clean


build: ## (Re-)build Docker images and containers with Docker compose
	docker-compose -f docker-compose.yml build


up: ## Start project services in console attached mode
	docker-compose -f docker-compose.yml -f docker-compose.override.yml up
up-detach: ## Start project services in daemon mode
	docker-compose -f docker-compose.yml -f docker-compose.override.yml up --detach

logs: ## View Docker containers log (if started in daemon mode)
	docker-compose -f docker-compose.yml -f docker-compose.override.yml logs
down: ## Stop Docker containers (if started in daemon mode)
	docker-compose -f docker-compose.yml -f docker-compose.override.yml down --remove-orphans


## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands
	$(SYMFONY_CMD)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY_CMD) cache:clear

wc: ## Warmup the cache
	$(SYMFONY_CMD) cache:warmup

fix-perms: ## Fix permissions of all var files
	chmod -R 777 var/*

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY_CMD) assets:install public/ --symlink --relative

purge: ## Purge cache and log
	rm -rf var/cache/* var/log/*


## —— Symfony binary 💻 ————————————————————————————————————————————————————————
cert-install: ## Install the local HTTPS certificates
	$(SYMFONY) server:ca:install

serve: ## Serve the application with HTTPS support (add "--no-tls" to disable https)
	$(SYMFONY) serve --daemon --port=$(HTTP_PORT)

unserve: ## Stop the webserver
	$(SYMFONY) server:stop


## —— Database ————————————————————————————————————————————————————————————————
db: ## Create and load the database with the tests fixtures
	$(SYMFONY_CMD) doctrine:database:create --if-not-exists
	$(SYMFONY_CMD) doctrine:schema:drop --force
	$(SYMFONY_CMD) doctrine:schema:create
	$(SYMFONY_CMD) doctrine:schema:validate
	#$(SYMFONY_CMD) doctrine:migrations:migrate --no-interaction
	$(SYMFONY_CMD) hautelook:fixtures:load --no-interaction -vv

db-drop: ## Drop the database if it exists
	$(SYMFONY_CMD) doctrine:database:drop --force --if-exists

## —— Coding standards ✨ ——————————————————————————————————————————————————————
cs: ## Run all coding standards checks
	$(COMPOSER) lint
	$(COMPOSER) phpstan


## —— Tests ✅ —————————————————————————————————————————————————————————————————
tests: phpunit.xml.dist ## Run all the tests
	$(COMPOSER) tests
unit-tests: phpunit.xml.dist ## Run the unit tests
	$(COMPOSER) tests-utils
	$(COMPOSER) tests-unit
api-tests: phpunit.xml.dist ## Run the API tests
	$(COMPOSER) tests-api
app-tests: phpunit.xml.dist ## Run the application tests
	$(COMPOSER) tests-app


ssh: ## Access Docker container terminal.
	docker exec -t -i $(CONTAINER_NAME) bash
