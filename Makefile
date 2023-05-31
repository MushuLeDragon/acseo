project_name=acseo
docker_username=mushuledragon
docker_project_name=$(project_name)
docker_db_name=$(project_name)_db
docker_version=latest

apache:
	docker exec -it ${project_name}_apache bash
php:
	docker exec -it ${project_name}_php bash
db:
	docker exec -it ${project_name}_db mysql -uroot -ptoor ${project_name}_db
db-dump:
	docker exec -it ${project_name}_db mysqldump -uroot -ptoor ${project_name}_db > dump.sql

docker-build:
	make docker-build-php
	make docker-build-db
docker-build-php:
	docker build . -f ./.docker/php/Dockerfile -t $(docker_username)/$(docker_project_name):$(docker_version)
docker-build-db:
	docker build . --no-cache -f ./.docker/db/Dockerfile -t $(docker_username)/$(docker_db_name):$(docker_version)

docker-push:
	make docker-push-php
	make docker-push-db
docker-push-php:
	docker push $(docker_username)/$(docker_project_name):$(docker_version)
docker-push-db:
	docker push $(docker_username)/$(docker_project_name)_db:$(docker_version)

yolo:
	make echo

echo:
	echo 'yolo'