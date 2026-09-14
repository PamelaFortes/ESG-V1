FROM php:8.2-apache

# Instala o driver PostgreSQL do PHP
RUN docker-php-ext-install pdo_pgsql

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia o projeto para o Apache
COPY . /var/www/html/

# Define a pasta do projeto
WORKDIR /var/www/html/

# Permissões básicas
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80