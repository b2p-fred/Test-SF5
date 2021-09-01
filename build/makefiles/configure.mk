#
# First installation: configure the project
#
# Constants
CACHEDIR=$(APP_ROOT_DIR)var/cache

#
# Commands
#

configure: composer project-dir database-grant

database-create:
	$(CONSOLE) prime:database:create -u root -p root

database-grant: database-create
ifeq ($(APPLICATION_ENV),integration)
	$(CONSOLE) database:grant --host % -u root -p root
	$(CONSOLE) database:grant --host localhost -u root -p root
else
    ifeq ($(APPLICATION_ENV),dev)
		$(CONSOLE) database:grant --host %
		$(CONSOLE) database:grant --host localhost
    else
		$(CONSOLE) database:grant
    endif
endif

project-dir: $(CACHEDIR)
	$(CONSOLE) configure

$(CACHEDIR):
	mkdir -p $(CACHEDIR) && chmod 777 $(CACHEDIR)


.PHONY: configure database-create database-grant project-dir
