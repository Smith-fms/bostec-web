FROM php:8.1-fpm

# Argumente definieren
ARG user=www-data
ARG uid=1000

# Abhängigkeiten installieren
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# PHP-Erweiterungen installieren
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer installieren
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Arbeitsverzeichnis erstellen
WORKDIR /var/www/html

# Benutzerberechtigungen anpassen
RUN chown -R www-data:www-data /var/www/html

# Benutzer wechseln
USER ${user}

# Container starten
CMD ["php-fpm"]
