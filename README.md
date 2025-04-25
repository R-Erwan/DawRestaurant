# DawRestaurant

## Authors
- [@Erwan](https://github.com/R-Erwan)
- [@Auréline](https://github.com/Aurel-2)
- [@Samuel](https://github.com/SamuelRibeiro98)


## Configurations
Modifier le fichier `/api/config/config.exemple.php` en `config.php` et modifier les paramètres

### Config de l'application :
`/api/config/config.php` :
```php
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'secret'); // Mot de passe de cryptage
define('ACTIVE_MAIL', getenv('ACTIVE_MAIL') ?: false); // Activer tous les mails
define('DOMAIN_NAME', getenv('DOMAIN_NAME') ?: 'http://localhost:8080'); // Domain Name macher le port avec Docker
define('MAIL_USER', getenv('MAIL_USER') ?: 'adresse@mail'); // Mail de contact
define('MAIL_PASS', getenv('MAIL_PASS') ?: '**********'); // Mot de passe d'application SMTP
define('MAIL_HOST', getenv('MAIL_HOST') ?: 'smtp.****.***'); // Host SMTP
define('DB_USER', getenv('DB_NAME') ?: 'admin'); // Utilisateur DB
define('DB_PASS', getenv('DB_PASS') ?: 'admin'); // Mot de passe DB
```

### Identifiants par défaut : 
- admin@leresto.com  : **admin**
- nobody@leresto.com : **nobody**

## Lancement avec docker 

- Installer docker sur votre système
- Lancer Docker

```bash
  docker-compose up -d --build
```

L'application écoute sur le port 8080 de la machine, par défaut.

