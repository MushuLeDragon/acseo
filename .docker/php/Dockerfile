FROM php:8.1.13-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl && \
    curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer && \
    apt update && apt install -yqq zip git

COPY . /var/www/

COPY ./.docker/php/apache.conf /etc/apache2/sites-available/000-default.conf

ENV APP_ENV=prod

RUN rm -rf /var/www/vendor && \
    rm -rf /var/www/.git && \
    rm -rf /var/www/.env.local && \
    # rm -rf /var/www/public/json && \
    cd /var/www && \
    composer install && \
    php bin/console cache:clear && \
    php bin/console cache:warmup && \
    chown -R www-data:www-data /var/www

RUN apt-get update && \
    apt-get install -y nodejs npm && \
    npm install

WORKDIR /var/www/

ENTRYPOINT ["bash", "./.docker/php/docker.sh"]

EXPOSE 80