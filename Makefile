login-php-cli: docker-compose.yml .env
	docker compose exec -u $(shell id -u):$(shell id -g) php-cli bash $(c)
db-migrate: docker-compose.yml .env
	docker compose exec php-cli php artisan migrate
build-dev: docker-compose.yml .env docker
	docker compose --profile=dev build
build: docker-compose.yml .env docker
	docker compose build
up-dev: docker docker-compose.yml .env
	docker compose --profile=dev up -d
up: docker docker-compose.yml .env
	docker compose up -d
down: docker-compose.yml docker .env
	docker compose down
down-dev: docker-compose.yml docker .env
	docker compose --profile=dev down
artisan-migrate: docker-compose.yml .env
	docker compose run --rm artisan migrate
phpstan: docker-compose.yml .env
	docker compose run --rm php-cli ./vendor/bin/phpstan --memory-limit=2G
