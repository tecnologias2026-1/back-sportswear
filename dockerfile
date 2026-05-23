FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev zip unzip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html

# Apache apuntará a /apis
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/apis|g' /etc/apache2/sites-available/000-default.conf

RUN sed -ri -e 's!/var/www/html!/var/www/html/apis!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]