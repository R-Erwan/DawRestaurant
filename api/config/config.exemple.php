<?php
/*
    Fichier de configuration d'exemple, à modifier avec vos informations / configurations
*/

define('JWT_SECRET', getenv('JWT_SECRET') ?: 'secret'); // Mot de passe de cryptage
define('ACTIVE_MAIL', getenv('ACTIVE_MAIL') ?: false); // Activer tous les mails
define('DOMAIN_NAME', getenv('DOMAIN_NAME') ?: 'http://localhost:8080'); // Domain Name a matcher avec Docker
define('MAIL_USER', getenv('MAIL_USER') ?: 'adresse@mail'); // Mail de contact
define('MAIL_PASS', getenv('MAIL_PASS') ?: '**********'); // Mot de passe d'application SMTP
define('MAIL_HOST', getenv('MAIL_HOST') ?: 'smtp.****.***'); // Host SMTP
define('DB_USER', getenv('DB_NAME') ?: 'admin'); // Utilisateur DB
define('DB_PASS', getenv('DB_PASS') ?: 'admin'); // Mot de passe DB

date_default_timezone_set('Europe/Paris'); // Fuseau horaire
