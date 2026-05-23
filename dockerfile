FROM php:8.1-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev zip unzip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html

# Cambiar Apache para usar /apis como raíz
ENV APACHE_DOCUMENT_ROOT=/var/www/html/apis

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN printf '%s\n' \
'<Directory /var/www/html/apis>' \
'    Options Indexes FollowSymLinks' \
'    AllowOverride All' \
'    Require all granted' \
'    DirectoryIndex index.php' \
'</Directory>' \
> /etc/apache2/conf-available/apis.conf \
&& a2enconf apis

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]