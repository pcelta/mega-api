.DEFAULT_GOAL := help

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

# Build the Docker images
.PHONY: build
build:
	@echo "Building the Docker images..."
	docker build -t mega-api-php-fpm . -f .dev/php/Dockerfile.fpm

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
