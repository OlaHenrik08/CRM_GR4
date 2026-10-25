FROM php:8.3-apache

RUN docker-php-ext-install mysqli \
    && a2enmod rewrite \
    && sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' \
       /etc/apache2/sites-available/000-default.conf

COPY ca.pem /var/www/html/ca.pem

EXPOSE 10000

CMD ["apache2-foreground"]
