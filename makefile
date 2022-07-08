.PHONY: create-folder-tree help bin login-php login-mongodb ps containers-logs clean-file-logs dev-build dev-up dev-start dev-stop dev-destroy prod-build prod-up
.SILENT:

include ./.env

## ----------------------------------------------------------------------
## This is a help message.
## Shows all available commands and their description.
## ----------------------------------------------------------------------

help:
	@sed -ne '/@sed/!s/## //p' $(MAKEFILE_LIST)

########################################

create-folder-tree:
	mkdir -p ./mnt/mongodb/
	mkdir -p ./var/log/nginx/
	mkdir -p ./var/log/php/

########################################

bin: ## Run application service command. Example: make bin c="help"
	docker exec -ti php sh -c "php -f ./bin/cli $(c)"

login-php: ## Login shell php container.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml exec php /bin/bash

login-mongodb: ## Login shell MongoSH.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml exec \
	mongodb sh -c "/usr/bin/mongosh mongodb://$(MONGO_USERNAME):$(MONGO_PASSWORD)@mongodb:27017/$(MONGO_DATABASE)"

ps: ## List services containers.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml ps

containers-logs: ## View output from containers.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml logs --tail=100 -f

clean-file-logs: ## Clean file logs.
	rm -rf ./var/log/*/*

########################################

dev-build: ## Build or rebuild (for development) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/dev.yml build

dev-up: create-folder-tree ## Create and start (for development) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/dev.yml up -d
	docker exec -ti php sh -c "composer update"

dev-start: ## Start (for development) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/dev.yml start

dev-stop: ## Stop (for development) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/dev.yml stop

dev-destroy: ## Destroy (for development) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/dev.yml down -v

########################################

prod-build: ## Build or rebuild (for production) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/prod.yml build

prod-up: create-folder-tree ## Create and start (for production) services.
	docker-compose --env-file ./.env -f ./etc/docker-compose/common.yml -f ./etc/docker-compose/prod.yml up -d
	docker exec -ti php sh -c "composer update --no-dev"
