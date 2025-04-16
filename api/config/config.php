<?php
define('JWT_SECRET', getenv('JWT_SECRET') ?: 'lesupersecret');
define('ACTIVE_MAIL', getenv('ACTIVE_MAIL') ?: false); // Activer tous les mails
?>
