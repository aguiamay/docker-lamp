FROM php:8.4-fpm

RUN docker-php-ext-install mysqli
RUN chmod -R 775 /var/www/html