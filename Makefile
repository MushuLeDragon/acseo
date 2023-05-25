project_name=acseo

apache:
	docker exec -it ${project_name}_apache bash

php:
	docker exec -it ${project_name}_php bash

db:
	docker exec -it ${project_name}_db mysql -uroot -ptoor ${project_name}_db