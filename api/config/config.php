<?php
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'lesupersecret');
define('ACTIVE_MAIL', getenv('ACTIVE_MAIL') ?: false); // Activer tous les mails
define('DOMAIN_NAME', getenv('DOMAIN_NAME') ?: 'http://localhost:8080');

date_default_timezone_set('Europe/Paris');
?>
