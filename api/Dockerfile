FROM php:8.3-apache
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql
COPY . /var/www/html/
