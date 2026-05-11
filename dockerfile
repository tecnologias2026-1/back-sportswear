FROM php:8.1-apache

# Instala dependencias del sistema y extensiones PHP comunes
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev zip unzip git libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

# Usar Composer oficial para dependencias
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar la aplicación
COPY . /var/www/html

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Instalar dependencias de PHP si existe composer.json
RUN if [ -f composer.json ]; then composer install --no-interaction --no-dev --prefer-dist; fi

EXPOSE 80

CMD ["apache2-foreground"]
