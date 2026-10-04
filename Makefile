.PHONY: help build deploy deploy-test remote

# Variables de déploiement (non versionnées) : SERVER_USER, SERVER_HOST, APP_PATH
ifneq (,$(wildcard .env.deploy))
include .env.deploy
export
endif

# Binaires côté serveur, surchargeables dans .env.deploy (ex. PHP=/usr/local/bin/php8.4)
PHP ?= php
COMPOSER ?= composer

ifeq ($(strip $(APP_PATH)),)
$(error APP_PATH est vide : sans lui, rsync --delete viserait tout le home du serveur)
endif

SSH = ssh $(SERVER_USER)@$(SERVER_HOST)
CONSOLE = cd ~/$(APP_PATH) && $(PHP) bin/console --env=prod --no-interaction

# Ce qui ne part jamais sur le serveur. Les fichiers exclus ne sont pas non plus
# supprimés par --delete : le .env.local et le var/ du serveur sont préservés.
RSYNC_EXCLUDES = \
	--exclude=/.git/ \
	--exclude=/.idea/ \
	--exclude=/.zed/ \
	--exclude=/node_modules/ \
	--exclude=/vendor/ \
	--exclude=/var/ \
	--exclude=/tests/ \
	--exclude=/.env.local \
	--exclude=/.env.*.local \
	--exclude=/.env.deploy \
	--exclude=/.phpunit.cache/ \
	--exclude=/tmp/ \
	--exclude=/phpunit.xml \
	$(RSYNC_PROTECT)

# Images uploadées depuis l'admin : elles n'existent que sur le serveur, --delete
# ne doit pas les effacer. Les images locales continuent d'être envoyées.
RSYNC_PROTECT = \
	--filter='P /public/images/projects/**'

help:
	@echo "make build        build Vite client + SSR (public/build, bootstrap/ssr)"
	@echo "make deploy-test  build + rsync --dry-run : affiche ce qui partirait"
	@echo "make deploy       build + rsync + composer install + cache + migrations"

build:
	npm ci
	npm run build

deploy-test: build
	rsync -av --itemize-changes --dry-run --delete $(RSYNC_EXCLUDES) \
		./ $(SERVER_USER)@$(SERVER_HOST):~/$(APP_PATH)

deploy: build
	rsync -av --itemize-changes --delete $(RSYNC_EXCLUDES) \
		./ $(SERVER_USER)@$(SERVER_HOST):~/$(APP_PATH)
	$(MAKE) remote

remote:
	$(SSH) 'cd ~/$(APP_PATH) && APP_ENV=prod $(COMPOSER) install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction'
	$(SSH) '$(CONSOLE) doctrine:migrations:migrate --allow-no-migration'
	$(SSH) '$(CONSOLE) cache:clear'
	# redémarre l'app Node SSR (Passenger) si elle est configurée dans cPanel
	$(SSH) 'mkdir -p ~/$(APP_PATH)/tmp && touch ~/$(APP_PATH)/tmp/restart.txt'
