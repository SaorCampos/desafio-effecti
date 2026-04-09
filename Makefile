APP_CONTAINER=app

setup:
	docker compose up -d --build
	cp -n .env.example .env || true
	docker compose exec $(APP_CONTAINER) php artisan key:generate
	sleep 1
	docker compose exec $(APP_CONTAINER) php artisan migrate
	docker compose exec $(APP_CONTAINER) php artisan config:clear
	docker compose exec $(APP_CONTAINER) php artisan cache:clear
	docker compose exec $(APP_CONTAINER) php artisan optimize:clear
	docker compose exec $(APP_CONTAINER) npm install && npm run build
	@echo "Aplicação rodando em: http://localhost:8011"

up:
	docker-compose up -d

down:
	docker-compose down

test:
	docker exec -it $(APP_CONTAINER) php artisan test

shell:
	docker exec -it $(APP_CONTAINER) bash

seed:
	docker exec -it $(APP_CONTAINER) php artisan db:seed
