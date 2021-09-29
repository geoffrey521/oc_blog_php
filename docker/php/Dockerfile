FROM php:8.0-apache
RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable mysqli pdo_mysql
RUN apt-get update && apt-get upgrade -y
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY config/uploads.ini /usr/local/etc/php/conf.d/uploads.ini