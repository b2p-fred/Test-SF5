#
# Testing
#

# Constants
TESTDIR=$(APP_ROOT_DIR)tests
PHPUNIT=$(APP_ROOT_DIR)vendor/bin/phpunit
PUARGS=
PHPS=php -S 127.0.0.1:5000

#
# Commands
#

tests: unit-tests integration-test
unit-tests: unit-web unit-admin unit-ws

unit-web:
	$(PHPUNIT) $(PUARGS) -c $(TESTDIR)/unit/web

unit-ws:
	$(PHPUNIT) $(PUARGS) -c $(TESTDIR)/unit/ws

unit-admin: admin-test-server
	@$(PHPUNIT) $(PUARGS) -c $(TESTDIR)/unit/admin
	kill -SIGINT $(SRV_PID)

admin-test-server:
	$(eval SRV_PID=$(shell $(PHPS) $(TESTDIR)/unit/admin/server.php > /dev/null & echo $$!))

integration-test: integration-test-server
	@$(PHPUNIT) $(PUARGS) -c $(TESTDIR)/integration
	kill -SIGINT $(SRV_PID)

integration-test-server:
	$(eval SRV_PID=$(shell $(PHPS) $(TESTDIR)/integration/service/server.php > /dev/null & echo $$!))

.PHONY: unit-tests unit tests unit-web unit-admin admin-test-server integration-test integration-test-server
