FROM php:8.2-apache
LABEL authors="gabrieldchr"

# Ajouter ici les extensions spécifiques dont votre application a besoin
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
    --no-install-recommends && \
    rm -rf /var/lib/apt/lists/*

# Active les extensions PHP nécessaires (exemples)
RUN docker-php-ext-install pdo pdo_mysql mysqli zip gd

WORKDIR /var/www/html

COPY www/ .

RUN chown -R www-data:www-data /var/www/html