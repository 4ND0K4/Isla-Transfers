FROM php:8.2-apache

# Instala extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia solo el contenido de `app` al contenedor
COPY app/ /var/www/html/



