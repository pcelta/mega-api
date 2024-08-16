.DEFAULT_GOAL := help
c ?=
path ?=

# Display help information
.PHONY: help
help:
	@echo "Usage:"
	@echo "  make <target>"
	@echo ""
	@echo "Targets:"
	@echo "  build               Build the Docker images\n"
	@echo "  start               Start the containers \n"
	@echo "  stop                Stop the containers \n"
	@echo "  composer            Run composer commands"
	@echo "                      Example #1: make composer c='require dep=ramsey/uuid'"
	@echo "                      Example #2: make composer c=install\n"
	@echo "  phpunit             Run the tests"
	@echo "                      Example: make phpunit [path=tests/unit/Entity]"

# Build the Docker images
.PHONY: build
build:
	@echo "Building the Docker images..."
	docker build -t mega-api-php-fpm . -f .dev/php/Dockerfile.fpm && docker build -t mega-api-tests . -f .dev/php/Dockerfile.tests

.PHONY: start
start:
	@echo "Starting containers..."
	docker-compose up -d

.PHONY: stop
stop:
	@echo "Stopping containers..."
	docker-compose stop

.PHONY: reset
reset:
	@echo "Reseting containers..."
	docker-compose stop && docker-compose rm -f

.PHONY: composer
composer:
	@echo "Running composer $(c)"
	docker run --rm --interactive --tty --volume $(PWD):/app mega-api-tests composer $(c)

.PHONY: phpunit
phpunit:
	@echo "Executing the unit tests..."
	docker  run --rm --interactive --tty --volume $(PWD):/app mega-api-tests vendor/bin/phpunit $(path)
