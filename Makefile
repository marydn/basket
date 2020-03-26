.PHONY: all build deps composer-install composer-update composer reload test run-tests start stop destroy doco rebuild

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

# Main targets
build: deps start
deps: composer-install

# Composer
composer-install: CMD=install
composer-update: CMD=update

composer composer-install composer-update:
	@docker run --rm --interactive --tty --user $(id -u):$(id -g) \
		--volume $(current-dir):/app \
		composer $(CMD) \
			--ignore-platform-reqs \
			--no-ansi \
			--no-interaction

reload:
	@docker-compose exec php-fpm kill -USR2 1

# Tests
test:
	@docker exec -it terminal-php make run-tests

run-tests:
	mkdir -p build/test_results/phpunit
	./vendor/bin/phpunit --exclude-group='disabled' --colors=always --testdox --log-junit build/test_results/phpunit/junit.xml tests

start: CMD=up -d
stop: CMD=stop
destroy: CMD=down

doco start stop destroy:
	@docker-compose $(CMD)

rebuild:
	@docker-compose build --pull --force-rm --no-cache
	make deps
	make start