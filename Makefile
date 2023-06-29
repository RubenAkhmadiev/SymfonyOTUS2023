CONTAINER_NAME = app

build:
	docker-compose build --no-cache
up:
	docker-compose up -d && echo 'Контейнер:' && docker ps | grep ${CONTAINER_NAME}
down:
	docker-compose down
exec:
	docker-compose exec -it ${CONTAINER_NAME} sh
fixtures:
	docker-compose exec -it ${CONTAINER_NAME} php bin/console doctrine:fixtures:load --purge-with-truncate
