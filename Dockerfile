FROM php:8.2-apache

# Installer les bibliothèques de développement PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Copier le fichier de configuration Apache
COPY ./config/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copier le code du front-end
COPY ./public /var/www/html/

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Exposer le port 80 pour le serveur Apache
EXPOSE 80
