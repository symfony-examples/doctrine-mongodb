
DC=docker-compose
PHP_CONTAINER=php
EXEC_PHP=$(DC) exec $(PHP_CONTAINER) php

.DEFAULT_GOAL := help
.PHONY: help
help : Makefile # Print commands help.
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##
## Project commands
##----------------------------------------------------------------------------------------------------------------------
.PHONY: install logs shell

logs: ## View containers logs.
	$(DC) logs -f $(filter-out $@,$(MAKECMDGOALS))

shell: ## Run bash shell in php container.
	$(DC) exec $(PHP_CONTAINER) sh

# todo: installation job

##
## Symfony commands
##----------------------------------------------------------------------------------------------------------------------
.PHONY: composer console data-fixtures

composer: ## Run composer in php container.
	$(EXEC_PHP) composer $(filter-out $@,$(MAKECMDGOALS))

console: ## Run symfony console in php container.
	$(EXEC_PHP) php bin/console $(filter-out $@,$(MAKECMDGOALS))

data-fixtures: ## Execute doctrine fixtures.
	$(EXEC_PHP) bin/console doctrine:mongodb:fixtures:load -n

##
## Quality tools
##----------------------------------------------------------------------------------------------------------------------
.PHONY: fix check-cs phpstan unit ci

fix: ## Run php cs fixer
	$(EXEC_PHP) vendor/bin/php-cs-fixer fix -vvv --config=.php-cs-fixer.dist.php --cache-file=.php-cs-fixer.cache $(filter-out $@,$(MAKECMDGOALS))

check-cs: ## Run php cs fixer
	$(MAKE) fix --dry-run

phpstan: ## Run phpstan
	$(EXEC_PHP) ./vendor/bin/phpstan analyse -c phpstan.neon

unit: ## Run unit tests
	$(EXEC_PHP) vendor/bin/phpunit

ci: ## Run all tests and code quality
	$(MAKE) fix
	$(MAKE) phpstan
	$(MAKE) unit