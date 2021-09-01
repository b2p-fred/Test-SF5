# Constants
ADMIN_CACHE_DIR=$(APP_ROOT_DIR)var/admin/cache
### GENERATEDDIR=$(APP_ROOT_DIR)var/generated
PUBLICDIR=$(APP_ROOT_DIR)public
MODELDIR=$(APP_ROOT_DIR)src
CONSOLE=$(APP_ROOT_DIR)bin/console
VERSION=`cat $(APP_ROOT_DIR)release.txt`

# Colors
GREY = /bin/echo -e "\x1b[30m$(1)\x1b[0m"
RED = /bin/echo -e "\x1b[31m$(1)\x1b[0m"
GREEN = /bin/echo -e "\x1b[32m$(1)\x1b[0m"
YELLOW = /bin/echo -e "\x1b[33m$1\x1b[0m"
BLUE = /bin/echo -e "\x1b[34m$(1)\x1b[0m"
PURPLE = /bin/echo -e "\x1b[35m$(1)\x1b[0m"
CYAN = /bin/echo -e "\x1b[36m$(1)\x1b[0m"
WHITE = /bin/echo -e "\x1b[37m$(1)\x1b[0m"

#
# Commands
#
info:
	@$(call GREEN, v$(VERSION))
	@$(call YELLOW, "MAKE command:")
	@echo "     - configure: Run for first installation then run make install"
	@echo "     - install: Install the project."
	@echo "     - optimize: Generate file for optimization."
	@echo "     - clean: Clean cache and generated files"
	@echo "     - tests: Launch tests."
	@echo ""
	@$(call YELLOW, "First install ?")
	@echo "     - Configure config/*.ini"
	@echo "     - Change your app name and/or your paths in 'src/Application.php'"
	@echo "     - Add your providers in 'src/Application.php'"
	@echo "     - Add your commands in 'src/Console.php'"
	@echo "     - Run the 'make configure'"
	@echo ""
	@$(call YELLOW, "Install a branch ?")
	@echo "     - Run the 'make install'"
	@echo "     - Run the 'make optimize' for code optimization (Should be not run on dev environment)"


include $(APP_ROOT_DIR)build/makefiles/*.mk


.PHONY: info
