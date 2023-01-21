.DEFAULT_GOAL := help

D = docker
DC = docker-compose
EXEC = $(D) exec -it newproject_php
EXECMYSQL = $(D) exec -it newproject_mysql
COMPOSER = $(EXEC) composer

ifndef CI_JOB_ID
	GREEN  := $(shell tput -Txterm setaf 2)
	YELLOW := $(shell tput -Txterm setaf 3)
	RESET  := $(shell tput -Txterm sgr0)
	TARGET_MAX_CHAR_NUM=30
endif

## Display this message
help:
	@echo "${YELLOW}Newproject${RESET} with API Platform "
	@awk '/^[a-zA-Z\-_0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")-1); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${GREEN}%-$(TARGET_MAX_CHAR_NUM)s${RESET} %s\n", helpCommand, helpMessage; \
		} \
		isTopic = match(lastLine, /^###/); \
	    if (isTopic) { \
			topic = substr($$1, 0, index($$1, ":")-1); \
			printf "\n${YELLOW}%s${RESET}\n", topic; \
		} \
	} { lastLine = $$0 }' $(MAKEFILE_LIST)


#################################
Project:

## Install project
install:
	@$(DC) up -d
	@$(EXEC) composer install
	@$(EXECMYSQL) mysql --user=root --password=root -e "GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%';"
	@$(EXEC) bin/console d:d:c --if-not-exists
	@$(EXEC) bin/console d:s:c
	@$(EXEC) bin/console d:d:c --if-not-exists --env=test
	@$(EXEC) bin/console d:s:c --env=test
	@$(EXEC) bin/console c:c

## Start project
up:
	@$(DC) up -d --remove-orphans --no-recreate

## Enter php container
php:
	@$(EXEC) bash

## Install composer dependencies
composer-install:
	@$(COMPOSER) install --optimize-autoloader

## Update SQL database schema
update-db:
	@$(EXEC) ./bin/console c:c
	@$(EXEC) ./bin/console d:s:u

## Run phpunit tests
phpunit:
	@$(EXEC) ./bin/phpunit $(call args,$@)

## Run deptrac
deptrac:
	@echo "${YELLOW}Checking Bounded contexts...${RESET}"
	@$(EXEC) vendor/bin/deptrac

## Enter database container
mysql:
	@$(EXECMYSQL) bash

## Stop project
stop:
	@$(DC) stop

## Tear down project
down:
	@$(DC) down
