APP_CONTAINER=app

setup:
	docker compose up -d --build
	cp -n .env.example .env || true
	docker compose exec $(APP_CONTAINER) php artisan key:generate --show || echo "Chave já existe"
	docker compose exec $(APP_CONTAINER) php artisan wayfinder:generate
	sleep 1
	docker compose exec $(APP_CONTAINER) php artisan migrate
	docker compose exec $(APP_CONTAINER) php artisan config:clear
	docker compose exec $(APP_CONTAINER) php artisan cache:clear
	docker compose exec $(APP_CONTAINER) php artisan optimize:clear
	docker compose exec $(APP_CONTAINER) npm install && npm run build
	docker compose exec -it $(APP_CONTAINER) npm run dev

up:
	docker compose up -d

down:
	docker compose down

test:
	docker exec -it effecti_app php artisan test

shell:
	docker exec -it effecti_app bash

seed:
	docker exec -it effecti_app php artisan db:seed

