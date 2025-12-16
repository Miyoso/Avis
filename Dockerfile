FROM php:apache

# Installation des dépendances système et extensions PHP
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        default-mysql-client \
        libonig-dev \
        libxml2-dev \
        zlib1g-dev \
    --no-install-recommends && \
    rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP requises
RUN docker-php-ext-install zip gd mysqli pdo pdo_mysql

# On active le mod_rewrite d'Apache (utile pour beaucoup de sites PHP)
RUN a2enmod rewrite

# NOTE : On reste en ROOT pour cette démo afin d'éviter les problèmes de port 80
# et simplifier la gestion des droits d'écriture pour l'upload.

EXPOSE 80

CMD ["apache2-foreground"]