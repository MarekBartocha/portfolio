# Wybieramy oficjalny obraz PHP z Apache
FROM php:8.2-apache

# Instalacja niezbędnych narzędzi systemowych
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo_mysql xml zip mbstring \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Ustawienie katalogu roboczego
WORKDIR /var/www/html

# Kopiowanie pliku konfiguracyjnego PHP (opcjonalnie)
# COPY ./php.ini /usr/local/etc/php/

# Instalacja Composera globalnie
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ustawienia Apache
# Włączenie mod_rewrite, ustawienie DocumentRoot na public/
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80
