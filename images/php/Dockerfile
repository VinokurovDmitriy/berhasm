FROM php:7.2-fpm

RUN apt-get update
RUN apt-get install -y curl git

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli

WORKDIR /var/www/public_html

ENTRYPOINT [ "php-fpm" ]