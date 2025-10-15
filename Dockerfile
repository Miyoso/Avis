FROM php:apache


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


RUN docker-php-ext-install zip gd mysqli pdo pdo_mysql


RUN useradd -m appuser && chown -R appuser:www-data /var/www/html


USER appuser


EXPOSE 80


CMD ["apache2-foreground"]
