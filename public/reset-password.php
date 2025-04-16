<?php
    if(!isset($_GET['token'])) {
        header('location: index.php');
    }
    $token = $_GET['token'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Réservation</title>
    <link rel="stylesheet" href="./css/reset-password.css">
    <link rel="icon" type="image/png" href=" ./resources/images/favicon.png">
</head>
<body>
    <div class="container">
        <div class="forgot-image">
            <img src="resources/static/resetpassword.jpg">
        </div>
        <div class="vertical-bar"></div>
        <div class="content">
            <h1>Reinitialiser le mot de passe</h1>
            <div class="reset-form">
                <input type="password" id="psw1" placeholder="Nouveau mot de passe">
                <input type="password" id="psw2" placeholder="Répéter le mot de passe">
                <span id="error-msg"></span>
            </div>
            <button id="send" class="disabled">Valider</button>

        </div>
    </div>
    <script type="module" src="/js/reset-password.js"></script>
</body>
</html>