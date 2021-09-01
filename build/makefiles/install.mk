#
# Installation: prepare the project files and database
#

# Constants

# Generated files
ENTRY_POINTS=$(wildcard $(PUBLICDIR)/*.php)
PRELOADS=$(addprefix $(GENERATEDDIR)/preload_, $(notdir $(ENTRY_POINTS)))
SERVICE_DIR=
ENV=$(APP_ROOT_DIR).env.local.php
ENVARGS ?=

#
# Commands
#

# Install
install: composer clean database
ifeq ($(APPLICATION_ENV),moa)
	cp $(APP_ROOT_DIR)/extra/dev/feature.php $(APP_ROOT_DIR)/public/
endif

database:
	$(CONSOLE) doctrine:schema-update $(MODELDIR) --execute
	$(CONSOLE) doctrine:migrations:migrate

composer:
ifeq ($(APPLICATION_ENV),dev)
	composer install --prefer-dist --working-dir=$(APP_ROOT_DIR)
else
	composer install --prefer-dist --optimize-autoloader --working-dir=$(APP_ROOT_DIR)
endif


# Optimize
optimize: $(GENERATEDDIR)/hydrators $(PRELOADS)

$(GENERATEDDIR)/hydrators:
	$(CONSOLE) prime:hydrator -i $(MODELDIR)

$(PRELOADS):
	@echo "preload don't work with symfony contract"
	#$(CONSOLE) preload $(PUBLICDIR)/$(patsubst preload_%,%,$(notdir $@)) -o $@

# Clean
clean: cache-clean hydrator-clean preload-clean env-clean

cache-clean:
	sudo $(CONSOLE) cache:clear

hydrator-clean:
	sudo rm -rf $(GENERATEDDIR)/hydrators

preload-clean:
	sudo rm -rf $(PRELOADS)

env-clean:
	sudo rm -f $(ENV)

tests-clean:
	rm -f /tmp/s2p-auth-admin.sqlite
	rm -f /tmp/s2p-auth-admin-edoc.sqlite
	rm -f /tmp/s2p-account-edoc.sqlite
	rm -f /tmp/s2p-account-other-realm.sqlite


# Divers
bumpcachekey:
	$(CONSOLE) bump --cacheKey $(VERSION) "$(APP_ROOT_DIR)config" "$(APP_ROOT_DIR)public/assets/js" "$(APP_ROOT_DIR)public/assets/css"

cron:
	$(CONSOLE) cron:generate $(APP_ROOT_DIR)extra/cron -o /etc/cron.d

env: $(ENV)

$(ENV):
	$(CONSOLE) dump-env $(ENVARGS)


.PHONY: install database composer optimize clean cache-clean hydrator-clean preload-clean bumcachekey cron
