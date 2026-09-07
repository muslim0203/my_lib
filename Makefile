# =============================================================================
# marketplace - Makefile
#
# B5: README ilgari operatorga tarmoq/volume'larni QO'LDA yaratishni aytardi
# ("docker volume create marketplace" ...). Endi shu ish "make init" da,
# idempotent (bor bo'lsa xato bermaydi) tarzda.
#
# PROD:  make prod-up / prod-down / prod-ps / prod-logs
# =============================================================================

COMPOSE      := docker compose
COMPOSE_PROD := docker compose -f docker-compose.prod.yml

.PHONY: help init login-php-cli db-migrate build-dev build up-dev up down down-dev \
        artisan-migrate phpstan test prod-build prod-up prod-down prod-ps prod-logs \
        prod-optimize prod-fpm-reload

help:
	@echo "init            - docker tarmog'i va volume'larini yaratish (bir marta)"
	@echo "up / up-dev     - lokal stack (dev profil DB+rabbitmq ni ham ko'taradi)"
	@echo "test            - phpunit ni CI konteynerida ishga tushirish"
	@echo "phpstan         - larastan"
	@echo "prod-up         - production compose (docker-compose.prod.yml)"
	@echo "prod-optimize   - deploydan keyingi kesh qurish + queue restart + opcache reload"

# -----------------------------------------------------------------------------
# Birinchi ishga tushirish. Hammasi IDEMPOTENT: qayta chaqirsa ham xavfsiz,
# mavjud volume'dagi MA'LUMOTLARGA TEGMAYDI.
# -----------------------------------------------------------------------------
init:
	docker network inspect marketplace >/dev/null 2>&1 || docker network create marketplace
	docker volume inspect marketplace  >/dev/null 2>&1 || docker volume create marketplace
	docker volume inspect rabbitmq     >/dev/null 2>&1 || docker volume create rabbitmq
	@echo "OK: 'marketplace' tarmog'i va volume'lar mavjud."
	@docker network inspect marketplace --format 'subnet: {{range .IPAM.Config}}{{.Subnet}}{{end}}'
	@echo "^ Shu subnet'ni docker/database/postgresql/pg_hba.conf da toraytirish tavsiya etiladi."

login-php-cli: docker-compose.yml .env
	$(COMPOSE) exec -u $(shell id -u):$(shell id -g) php-cli bash $(c)

db-migrate: docker-compose.yml .env
	$(COMPOSE) exec php-cli php artisan migrate

build-dev: docker-compose.yml .env docker
	$(COMPOSE) --profile=dev build

build: docker-compose.yml .env docker
	$(COMPOSE) build

up-dev: docker docker-compose.yml .env
	$(COMPOSE) --profile=dev up -d

up: docker docker-compose.yml .env
	$(COMPOSE) up -d

down: docker-compose.yml docker .env
	$(COMPOSE) down

down-dev: docker-compose.yml docker .env
	$(COMPOSE) --profile=dev down

# Eski varianti "docker compose run --rm artisan migrate" edi - "artisan"
# nomli servis compose'da UMUMAN YO'Q, ya'ni bu target hech qachon ishlamagan.
artisan-migrate: docker-compose.yml .env
	$(COMPOSE) run --rm php-cli php artisan migrate

phpstan: docker-compose.yml .env
	$(COMPOSE) run --rm php-cli ./vendor/bin/phpstan analyse --memory-limit=2G

# CI dagi bilan bir xil muhitda (tarmoqsiz, dev-bog'liqliklar bilan).
test: docker-compose-ci-cd.yml
	docker compose -f docker-compose-ci-cd.yml run --rm --build php_deploy ./vendor/bin/phpunit --colors=never

# -----------------------------------------------------------------------------
# PRODUCTION
# DB/broker default holatda TASHQI xizmatlar. Ular ham shu hostda kerak bo'lsa:
#     $(COMPOSE_PROD) --profile selfhosted up -d
# -----------------------------------------------------------------------------
prod-build: docker-compose.prod.yml .env docker
	$(COMPOSE_PROD) build

prod-up: docker-compose.prod.yml .env docker
	$(COMPOSE_PROD) up -d

prod-down: docker-compose.prod.yml
	$(COMPOSE_PROD) down

prod-ps: docker-compose.prod.yml
	$(COMPOSE_PROD) ps

prod-logs: docker-compose.prod.yml
	$(COMPOSE_PROD) logs -f --tail=200

# Deploydan keyingi qadamlar (CI dagi app-optimize-prod bilan bir xil).
prod-optimize:
	$(COMPOSE_PROD) exec queue php artisan optimize:clear
	$(COMPOSE_PROD) exec queue php artisan config:cache
	$(COMPOSE_PROD) exec queue php artisan route:cache
	$(COMPOSE_PROD) exec queue php artisan view:cache
	$(COMPOSE_PROD) exec queue php artisan storage:link --force
	$(COMPOSE_PROD) exec queue php artisan queue:restart
	$(MAKE) prod-fpm-reload

# opcache.validate_timestamps=0 bo'lgani uchun FPM ni graceful reload qilish SHART.
prod-fpm-reload:
	$(COMPOSE_PROD) exec php kill -USR2 1
