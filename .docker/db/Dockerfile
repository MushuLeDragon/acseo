FROM mysql:latest

ENV MYSQL_ROOT_PASSWORD toor
COPY ./dump.sql /docker-entrypoint-initdb.d

EXPOSE 3306