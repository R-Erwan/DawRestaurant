FROM php:8.3-apache

# Installer les bibliothèques de développement PostgreSQL et curl
RUN apt-get update && apt-get install -y libpq-dev curl

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql

# Installer Node.js et npm
#RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
#    apt-get install -y nodejs

# Copier le fichier de configuration Apache
COPY ./config/000-defaultFront.conf /etc/apache2/sites-available/000-default.conf

# Copier le code du front-end
COPY ./public /var/www/html/

# Se placer dans le répertoire de ton front-end (si tu l'as)
WORKDIR /var/www/html

RUN chmod 777 -R resources

# Installer les dépendances npm (assurez-vous que package.json existe dans ce répertoire)
#RUN npm install

# Activer les modules Apache nécessaires
RUN a2enmod proxy proxy_http
RUN a2enmod rewrite

# Exposer le port 80 pour le serveur Apache
EXPOSE 80