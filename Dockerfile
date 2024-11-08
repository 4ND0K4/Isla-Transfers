FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /var/www/html
COPY PHPower-Isla_Transfers/ /var/www/html/